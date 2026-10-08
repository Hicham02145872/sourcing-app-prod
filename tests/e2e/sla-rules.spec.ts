import { test, expect, type Page } from '@playwright/test';
import { execSync } from 'node:child_process';
import * as fs from 'node:fs';
import * as path from 'node:path';
import { dismissSlaLoginPopup } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';
const ROOT = process.cwd();

function run(args: string): string {
  return execSync(`php artisan sla:e2e ${args}`, {
    cwd: ROOT,
    encoding: 'utf8',
  }).trim();
}

interface SlaState {
  admin_id: number | null;
  in_review: Record<string, unknown>;
  negotiating: Record<string, unknown>;
  paid: Record<string, unknown>;
  china: Record<string, unknown>;
}

function state(): SlaState {
  const out = run('state');
  const line = out.split('\n').find((l) => l.trim().startsWith('{'));
  if (!line) {
    throw new Error(`No JSON state returned by sla:e2e state.\n${out}`);
  }

  return JSON.parse(line) as SlaState;
}

async function loginSlaAdmin(page: Page): Promise<void> {
  await gotoResilient(page, `${BASE_URL}/eng/login`);
  await page.fill('input[name="email"]', 'sla.e2e@example.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/admin/dashboard', { timeout: 30_000 });
  // The login popup (blocked items) must be closed before further assertions.
  await dismissSlaLoginPopup(page);
}

async function gotoResilient(page: Page, url: string): Promise<void> {
  for (let attempt = 0; attempt < 4; attempt += 1) {
    const response = await page.goto(url, { waitUntil: 'domcontentloaded' });
    const body = await page.textContent('body').catch(() => '');
    const rateLimited = (response && response.status() === 429) ||
      (body.includes('Too Many Requests') || body.includes('429'));
    if (rateLimited) {
      await page.waitForTimeout(2500 * (attempt + 1));
      continue;
    }
    await page.waitForLoadState('networkidle').catch(() => undefined);

    return;
  }

  throw new Error(`Repeated 429 rate limiting while loading ${url}`);
}

async function expectSlaBannerAndSidebar(
  page: Page,
  opts: { hrefPattern: string; hint: string },
): Promise<void> {
  await expect(page.getByText('Action needed on your folders').first()).toBeVisible();
  const sidebarAction = page
    .locator('aside a')
    .filter({ hasText: 'Process SLA' })
    .first();
  await expect(sidebarAction).toContainText('Process SLA');
  // The shortcut adapts to the most urgent SLA: item type in the hint and
  // the target page in the href (quotation for requests, order for orders).
  await expect(sidebarAction).toContainText(opts.hint);
  await expect(sidebarAction).toHaveAttribute('href', new RegExp(opts.hrefPattern));
}

async function fillQuotationForm(
  page: Page,
  options: { withUnitPrice?: boolean } = {},
): Promise<void> {
  if (options.withUnitPrice) {
    await page.fill('input[name="unit_price"]:visible', '12.50');
  }
  await page.fill('input[name="commission_service"]:visible', '25');
  await page.fill('input[name="delivery_cost_china"]:visible', '30');
  await page.selectOption('select[name="currency"]:visible', 'USD');
}

test.describe('SLA workflow — end-to-end across all rules', () => {
  test.describe.configure({ mode: 'serial' });

  test.beforeAll(async () => {
    run('seed');

    const dir = path.join(ROOT, 'tests', 'e2e', 'assets');
    fs.mkdirSync(dir, { recursive: true });
    const png = Buffer.from(
      'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9WlXZ1sAAAAASUVORK5CYII=',
      'base64',
    );
    fs.writeFileSync(path.join(dir, 'parcel.png'), png);
  });

  test('Rule 1 — request in_review (24h): flag → lock → quotations.create → resolve → unlock', async ({
    page,
  }) => {
    run('reset --scenario=in_review');
    run('flag');

    const st = state();
    expect(st.in_review.request_restricted).toBe(true);

    await loginSlaAdmin(page);

    // Dashboard is the landing page; a blocked route must redirect to the quotation page.
    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForURL('**/admin/quotations/create/**');
    const createUrl = page.url();
    expect(createUrl).toContain('/admin/quotations/create/');

    await expectSlaBannerAndSidebar(page, {
      hrefPattern: '/admin/quotations/create/',
      hint: '1 folder(s) to process',
    });

    // The overdue list (allowed under the lock) shows the folder with its "Process" action.
    await gotoResilient(page, `${BASE_URL}/admin/sourcing-requests?overdue=1&admin_id=me`);
    await page.waitForLoadState('networkidle');
    await expect(page.getByText('Action needed on your folders').first()).toBeVisible();
    await expect(
      page
        .locator(`a[href*="/admin/quotations/create/${st.in_review.request_id}"]`)
        .first(),
    ).toBeVisible();

    // Resolution: create the quotation.
    await gotoResilient(page, createUrl);
    await fillQuotationForm(page);
    await page.getByRole('button', { name: 'Create Quotation' }).click();
    await page.waitForURL('**/admin/dashboard', { timeout: 30_000 });

    const st2 = state();
    expect(st2.in_review.request_status).toBe('quoted');
    expect(st2.in_review.request_restricted).toBe(false);

    // The lock is released: the previously-blocked quotation index now loads.
    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');
    expect(page.url()).toContain('/admin/quotations');
  });

  test('Rule 2 — request negotiating (24h): flag → lock → quotations.edit → update → unlock', async ({
    page,
  }) => {
    run('reset --scenario=negotiating');
    run('flag');

    const st = state();
    expect(st.negotiating.request_restricted).toBe(true);

    await loginSlaAdmin(page);

    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForURL('**/admin/quotations/*/edit');
    expect(page.url()).toContain('/admin/quotations/');

    await expectSlaBannerAndSidebar(page, {
      hrefPattern: '/admin/quotations/\\d+/edit',
      hint: '1 folder(s) to process',
    });

    // Resolution: update the quotation (sends it back to the client as quoted).
    await fillQuotationForm(page, { withUnitPrice: true });
    await page.getByRole('button', { name: 'Update Quotation' }).click();
    await page.waitForURL('**/admin/sourcing-requests/**', { timeout: 30_000 });

    const st2 = state();
    expect(st2.negotiating.request_status).toBe('quoted');
    expect(st2.negotiating.request_restricted).toBe(false);

    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');
    expect(page.url()).toContain('/admin/quotations');
  });

  test('Rule 3 — order paid (24h): flag → lock → order page → move to shipping → unlock', async ({
    page,
  }) => {
    run('reset --scenario=paid');
    run('flag');

    const st = state();
    expect(st.paid.order_restricted).toBe(true);

    await loginSlaAdmin(page);

    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForURL('**/admin/sourcing-orders/**');
    expect(page.url()).toContain(`/admin/sourcing-orders/${st.paid.order_id}`);

    await expectSlaBannerAndSidebar(page, {
      hrefPattern: '/admin/sourcing-orders/',
      hint: '1 order(s) to process',
    });

    // Resolution: from the (allowed) order list, move the paid order to shipping.
    page.once('dialog', (d) => d.accept());
    await gotoResilient(page, `${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');
    const select = page
      .locator(
        `form[action*="/admin/sourcing-orders/${st.paid.order_id}"] select[name="status"]`,
      )
      .first();
    await select.selectOption('shipment_preparing');
    await page.waitForLoadState('networkidle');

    const st2 = state();
    expect(st2.paid.order_status).toBe('shipment_preparing');
    expect(st2.paid.order_restricted).toBe(false);

    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');
    expect(page.url()).toContain('/admin/quotations');
  });

  test('Rule 4 — order in_transit_china (48h): flag → lock → next status blocked → evidence unlocks', async ({
    page,
  }) => {
    run('reset --scenario=china');
    run('flag');

    const st = state();
    expect(st.china.order_restricted).toBe(true);

    await loginSlaAdmin(page);

    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForURL('**/admin/sourcing-orders/**');
    expect(page.url()).toContain(`/admin/sourcing-orders/${st.china.order_id}`);

    await expectSlaBannerAndSidebar(page, {
      hrefPattern: '/admin/sourcing-orders/',
      hint: '1 order(s) to process',
    });

    // Rule 2 is disabled: advancing to the next status without evidence is refused.
    page.once('dialog', (d) => d.accept());
    await gotoResilient(page, `${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');
    await page
      .locator(
        `form[action*="/admin/sourcing-orders/${st.china.order_id}"] select[name="status"]`,
      )
      .first()
      .selectOption('arrival_uae');
    await page.waitForLoadState('networkidle');

    const st2 = state();
    expect(st2.china.order_status).toBe('in_transit_china');
    expect(st2.china.order_restricted).toBe(true);

    // Rule 1: the parcel photo alone is not enough (no China tracking yet).
    await gotoResilient(page, `${BASE_URL}/admin/sourcing-orders/${st.china.order_id}`);
    await page.setInputFiles('input[name="parcel_photo"]', [
      path.join(ROOT, 'tests', 'e2e', 'assets', 'parcel.png'),
    ]);
    await page.fill('input[name="parcel_weight_kg"]', '2');
    await page
      .locator('form[action*="parcel"] button[type="submit"]')
      .click();
    await page.waitForLoadState('networkidle');

    const st3 = state();
    expect(st3.china.parcel_uploaded).toBe(true);
    expect(st3.china.order_restricted).toBe(true);

    // Rule 1 completed: China tracking saved → the deadline is satisfied.
    await page.fill('#evidence_tracking_number', 'ME123456789CN');
    await page.getByRole('button', { name: 'Save evidence' }).click();
    await page.waitForLoadState('networkidle');

    const st4 = state();
    expect(st4.china.china_tracking).toBe(true);
    expect(st4.china.order_restricted).toBe(false);

    // With evidence in place the normal advance works again.
    page.once('dialog', (d) => d.accept());
    await gotoResilient(page, `${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');
    await page
      .locator(
        `form[action*="/admin/sourcing-orders/${st.china.order_id}"] select[name="status"]`,
      )
      .first()
      .selectOption('arrival_uae');
    await page.waitForLoadState('networkidle');

    const st5 = state();
    expect(st5.china.order_status).toBe('arrival_uae');
    expect(st5.china.order_restricted).toBe(false);

    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');
    expect(page.url()).toContain('/admin/quotations');
  });

  test('Priority — all rules overdue: in_review first, then negotiating after resolution', async ({
    page,
  }) => {
    run('reset --scenario=all');
    run('flag');

    const st = state();
    expect(st.in_review.request_restricted).toBe(true);
    expect(st.negotiating.request_restricted).toBe(true);
    expect(st.paid.order_restricted).toBe(true);
    expect(st.china.order_restricted).toBe(true);

    await loginSlaAdmin(page);

    // in_review (priority 0) wins.
    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForURL('**/admin/quotations/create/**');

    // Resolve the in_review folder first.
    await fillQuotationForm(page);
    await page.getByRole('button', { name: 'Create Quotation' }).click();
    await page.waitForURL('**/admin/dashboard', { timeout: 30_000 });

    // Next urgent: the negotiating request (priority 1) redirects to its edit page.
    await gotoResilient(page, `${BASE_URL}/admin/quotations`);
    await page.waitForURL('**/admin/quotations/*/edit');
    expect(page.url()).toContain('/admin/quotations/');
  });
});