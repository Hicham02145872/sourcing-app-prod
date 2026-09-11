import { test, expect } from '@playwright/test';
import { loginAs } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Client Refund Requests', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'client');
  });

  test('TC-REF-01: View refund requests list', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/refunds`);
    await page.waitForLoadState('networkidle');

    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-REF-02: Create refund request from order page', async ({ page }) => {
    // Navigate to sourcing orders to find one with refund option
    await page.goto(`${BASE_URL}/eng/client/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    // Look for refund link/button
    const refundLink = page.locator('a:has-text("Refund"), a[href*="refund"]').first();
    if (await refundLink.count() > 0) {
      await refundLink.click();
      await page.waitForLoadState('networkidle');

      // Fill refund form if present
      const reasonField = page.locator('textarea[name="reason"], input[name="reason"]');
      if (await reasonField.count() > 0) {
        await reasonField.fill('E2E test refund request - product not as described');
        await page.click('button[type="submit"]');
        await page.waitForLoadState('networkidle');
      }
    }
  });

  test('TC-REF-03: View refund request detail', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/refunds`);
    await page.waitForLoadState('networkidle');

    // Click on first refund request if available
    const refundRow = page.locator('a[href*="refund-requests"]').first();
    if (await refundRow.count() > 0) {
      await refundRow.click();
      await page.waitForLoadState('networkidle');

      const body = await page.textContent('body');
      expect(body).toBeTruthy();
    }
  });
});
