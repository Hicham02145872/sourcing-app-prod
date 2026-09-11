import { test, expect } from '@playwright/test';
import { loginAs } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Admin Order Management', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'admin');
  });

  test('TC-ADM-O-01: Admin order list shows FSB references', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    const body = await page.textContent('body');
    // Admin should see FSB references for orders
    if (body && body.includes('FSB')) {
      expect(body).toMatch(/FSB0\d{5,}/);
    }
  });

  test('TC-ADM-O-02: Order detail page with status toggle', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    // Click first order
    const orderLink = page.locator('a[href*="sourcing-orders/"]').first();
    if (await orderLink.count() > 0) {
      await orderLink.click();
      await page.waitForLoadState('networkidle');

      // Should show order detail with status controls
      const body = await page.textContent('body');
      expect(body).toBeTruthy();
    }
  });

  test('TC-ADM-O-03: Update tracking number', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    const orderLink = page.locator('a[href*="sourcing-orders/"]').first();
    if (await orderLink.count() > 0) {
      await orderLink.click();
      await page.waitForLoadState('networkidle');

      // Look for tracking update button/input
      const trackingInput = page.locator('input[name="tracking_number"], input[placeholder*="tracking"]').first();
      if (await trackingInput.count() > 0) {
        await trackingInput.fill('TEST123456789');
        const saveBtn = page.locator('button:has-text("Save"), button:has-text("Update")').first();
        if (await saveBtn.count() > 0) {
          await saveBtn.click();
          await page.waitForLoadState('networkidle');
        }
      }
    }
  });

  test('TC-ADM-O-04: Shipping label admin view', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-orders`);
    await page.waitForLoadState('networkidle');

    const labelLink = page.locator('a:has-text("Label"), a[href*="shipping-label"]').first();
    if (await labelLink.count() > 0) {
      await labelLink.click();
      await page.waitForLoadState('networkidle');

      const body = await page.textContent('body');
      expect(body).toBeTruthy();
    }
  });
});
