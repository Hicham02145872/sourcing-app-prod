import { test, expect, type Page } from '@playwright/test';

const BASE_URL = process.env.BASE_URL || 'https://preprod.fastsourcingbrothers.com';

async function devLogin(page: Page): Promise<void> {
  await page.goto(`${BASE_URL}/dev/login`, { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', 'dev.test@example.com');
  await page.fill('input[name="password"]', 'password123');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/admin/dev-dashboard', { timeout: 30_000 });
}

test.describe('Dev Dashboard · SLA Simulator', () => {
  test('lists SLA-tracked items, simulates the 24h deadline, then clears it', async ({ page }) => {
    await devLogin(page);

    await page.locator('button', { hasText: 'SLA SIMULATOR' }).first().click();
    await expect(page.getByText('Sourcing Requests')).toBeVisible({ timeout: 15_000 });

    const reqSection = page.locator('section').filter({ hasText: 'Sourcing Requests' });
    const orderSection = page.locator('section').filter({ hasText: 'Sourcing Orders' });

    // Request FSB000115 (in_review, 24h) and order FSB000130 (paid, 24h) are tracked.
    const reqRow = reqSection.locator('div.flex', { hasText: 'FSB000115' }).first();
    await expect(reqRow).toContainText('in_review');
    await expect(reqRow).toContainText('deadline 24h');

    const orderRow = orderSection.locator('div.flex', { hasText: 'FSB000130' }).first();
    await expect(orderRow).toContainText('paid');
    await expect(orderRow).toContainText('deadline 24h');

    // Simulate SLA exceeded on the in_review request FSB000115.
    await reqRow.getByRole('button', { name: 'Simuler SLA dépassé' }).click();
    await expect(reqRow).toContainText('Overdue / Restricted', { timeout: 20_000 });

    // Clear the restriction.
    await reqRow.getByRole('button', { name: 'Lever' }).click();
    await expect(reqRow).toContainText('OK', { timeout: 20_000 });
  });
});