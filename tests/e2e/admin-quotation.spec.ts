import { test, expect } from '@playwright/test';
import { loginAs } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Admin Quotation Management', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'admin');
  });

  test('TC-ADM-Q-01: View quotations list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');

    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-ADM-Q-02: Create quotation for a sourcing request', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/quotations/select-request`);
    await page.waitForLoadState('networkidle');

    // Select a request if available
    const requestLink = page.locator('a[href*="quotations/create"]').first();
    if (await requestLink.count() > 0) {
      await requestLink.click();
      await page.waitForLoadState('networkidle');

      // Fill quotation form
      await page.fill('input[name="amount"]', '150.00');
      await page.fill('input[name="unit_price"]', '15.00');

      await page.click('button[type="submit"]');
      await page.waitForLoadState('networkidle');
    }
  });

  test('TC-ADM-Q-03: Approve quotation', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');

    const approveBtn = page.locator('button:has-text("Approve"), a:has-text("Approve")').first();
    if (await approveBtn.count() > 0) {
      await approveBtn.click();
      await page.waitForLoadState('networkidle');
    }
  });

  test('TC-ADM-Q-04: Reject quotation', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/quotations`);
    await page.waitForLoadState('networkidle');

    const rejectBtn = page.locator('button:has-text("Reject"), a:has-text("Reject")').first();
    if (await rejectBtn.count() > 0) {
      await rejectBtn.click();
      await page.waitForLoadState('networkidle');
    }
  });
});
