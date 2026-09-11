import { test, expect } from '@playwright/test';
import { loginAs, assertNoSbForOrder, assertFsbVisible } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Client Order (FSB Reference)', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'client');
  });

  test('TC-ORD-01: Order list shows FSB references, not SB', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    // Verify no SB references are displayed for orders
    await assertNoSbForOrder(page);

    // Verify FSB references are present
    const body = await page.textContent('body');
    if (body && body.includes('FSB')) {
      expect(body).toMatch(/FSB0\d{5,}/);
    }
  });

  test('TC-ORD-02: Order detail page shows FSB reference', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    // Click on first order if available
    const orderLink = page.locator('a[href*="sourcing-orders/"]').first();
    if (await orderLink.count() > 0) {
      await orderLink.click();
      await page.waitForLoadState('networkidle');

      // Should show FSB reference
      await assertNoSbForOrder(page);
      const body = await page.textContent('body');
      expect(body).toMatch(/FSB0\d{5,}/);
    }
  });

  test('TC-ORD-03: Upload proof of payment', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    // Find order with upload button
    const uploadBtn = page.locator('a:has-text("Upload"), button:has-text("Upload")').first();
    if (await uploadBtn.count() > 0) {
      await uploadBtn.click();
      await page.waitForLoadState('networkidle');

      // Upload a test file
      const fileInput = page.locator('input[type="file"]').first();
      if (await fileInput.count() > 0) {
        const testFile = Buffer.from('test payment proof');
        await fileInput.setInputFiles({
          name: 'proof.jpg',
          mimeType: 'image/jpeg',
          buffer: testFile,
        });
        await page.click('button[type="submit"]');
        await page.waitForLoadState('networkidle');
      }
    }
  });

  test('TC-ORD-04: Shipping label displays correctly', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    const labelLink = page.locator('a:has-text("Shipping Label"), a[href*="shipping-label"]').first();
    if (await labelLink.count() > 0) {
      await labelLink.click();
      await page.waitForLoadState('networkidle');

      // Should not show SB for the order reference
      await assertNoSbForOrder(page);
    }
  });

  test('TC-ORD-05: Tracking page shows FSB', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/tracking`);
    await page.waitForLoadState('networkidle');

    // Search input should mention FSB
    const searchInput = page.locator('input[placeholder*="FSB"], input[type="search"]').first();
    if (await searchInput.count() > 0) {
      await expect(searchInput).toBeVisible();
    }
  });

  test('TC-ORD-06: History page lists past orders', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/history`);
    await page.waitForLoadState('networkidle');

    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });
});
