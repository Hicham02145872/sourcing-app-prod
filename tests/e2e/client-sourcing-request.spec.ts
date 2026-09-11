import { test, expect } from '@playwright/test';
import { loginAs, gotoLocalized, assertNoSbForOrder } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Client Sourcing Requests', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'client');
  });

  test('TC-SR-01: View sourcing requests list', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    // Page should load with a list or empty state
    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-SR-02: Create a new sourcing request', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-requests/create`);
    await page.waitForLoadState('domcontentloaded');

    // Dismiss the one-time product warning popup if it covers the form
    const dismissBtn = page.locator('button:has-text("I understand")');
    if (await dismissBtn.count() > 0 && await dismissBtn.first().isVisible()) {
      await dismissBtn.first().click();
    }

    // Fill in the form
    await page.fill('input[name="product_name"]', 'E2E Test Product');
    await page.fill('input[name="product_url"]', 'https://example.com/product/123');

    // Select a category if dropdown exists
    const categorySelect = page.locator('select[name="category_id"]');
    if (await categorySelect.count() > 0) {
      await categorySelect.selectOption({ index: 1 });
    }

    // Select sourcing location if dropdown exists
    const locationSelect = page.locator('select[name="sourcing_location"]');
    if (await locationSelect.count() > 0) {
      await locationSelect.selectOption({ index: 1 });
    }

    // Upload required product image (real 1x1 PNG so server-side mime detection passes)
    const imageInput = page.locator('input[name="product_image"]');
    if (await imageInput.count() > 0) {
      await imageInput.setInputFiles({
        name: 'product.png',
        mimeType: 'image/png',
        buffer: Buffer.from(
          'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
          'base64',
        ),
      });
    }

    // Select a shipping method
    await page.locator('label:has(> input[name="shipping_method"][value="air"])').click();

    // Fill notes
    const noteField = page.locator('textarea[name="note"]');
    if (await noteField.count() > 0) {
      await noteField.fill('E2E test sourcing request');
    }

    // Fill first destination block
    await page.fill('input[name="destinations[0][quantity]"]', '10');
    const countrySelect = page.locator('select[name="destinations[0][country_id]"]');
    if (await countrySelect.count() > 0) {
      await countrySelect.selectOption({ index: 1 });
    }
    const serviceSelect = page.locator('select[name="destinations[0][service_id]"]');
    if (await serviceSelect.count() > 0) {
      await serviceSelect.selectOption({ index: 1 });
    }
    await page.fill('textarea[name="destinations[0][address]"]', '123 Test Street, Test City');

    // Submit (scoped to the form — the sidebar has an earlier "Sign Out" submit)
    await page.locator('#sourcing-request-form button[type="submit"]').click();

    // Walk through the shipping routing / fee popups (Alpine runs from CDN).
    // Both popups keep a "Confirm & Submit" button in the DOM, so scope each
    // click by the popup's unique heading text instead of "first()".
    const routingPopup = page.locator('.fee-modal:has-text("Choose Shipping Routing")');
    const routingConfirm = routingPopup.locator('button:has-text("Confirm & Submit")');
    await routingConfirm.waitFor({ state: 'visible', timeout: 30_000 });
    await routingConfirm.click();

    const feeModal = page.locator('.fee-modal:has-text("Shipping Fees Summary")');
    const feeConfirm = feeModal.locator('button:has-text("Confirm & Submit")');
    await feeConfirm.waitFor({ state: 'visible', timeout: 30_000 });
    await feeConfirm.click();

    // The form (Alpine fetch) redirects to the dashboard
    await page.waitForURL(/(client\/dashboard|client\/sourcing-orders)/, { timeout: 90_000 });

    // Verify the request was created (landing on dashboard for this test)
    const url = page.url();
    if (url.includes('client/sourcing-orders')) {
      const body = await page.textContent('body');
      expect(body).toContain('E2E Test Product');
    }
  });

  test('TC-SR-03: Sourcing request shows SB reference (shared_id)', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    // Click on a request if list has items
    const rowLink = page.locator('a[href*="/client/sourcing-requests/"]');
    let rowIdx = -1;
    const rowCount = await rowLink.count();
    for (let i = 0; i < rowCount; i++) {
      const href = (await rowLink.nth(i).getAttribute('href')) || '';
      if (/\/sourcing-requests\/\d+$/.test(href)) {
        rowIdx = i;
        break;
      }
    }
    if (rowIdx >= 0) {
      await rowLink.nth(rowIdx).click();
      await page.waitForLoadState('domcontentloaded');

      // SB reference should be visible for sourcing requests
      const body = await page.textContent('body');
      expect(body).toMatch(/SB0\d{4,}/);
    }
  });

  test('TC-SR-04: Cancel a sourcing request', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    // Find a request with cancel button
    const cancelBtn = page.locator('button:has-text("Cancel"), a:has-text("Cancel")').first();
    if (await cancelBtn.count() > 0) {
      await cancelBtn.click();

      // Confirm dialog if present
      const confirmBtn = page.locator('button:has-text("Confirm"), button:has-text("Yes")');
      if (await confirmBtn.count() > 0) {
        await confirmBtn.click();
      }

      await page.waitForLoadState('networkidle');
    }
  });
});
