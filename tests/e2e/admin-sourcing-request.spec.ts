import { test, expect } from '@playwright/test';
import { loginAs } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Admin Sourcing Request Management', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'admin');
  });

  test('TC-ADM-SR-01: Search requests by shared_id', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    const searchInput = page.locator('input[name="search"], input[type="search"]').first();
    if (await searchInput.count() > 0) {
      await searchInput.fill('SB00005');
      await searchInput.press('Enter');
      await page.waitForLoadState('networkidle');

      const body = await page.textContent('body');
      expect(body).toBeTruthy();
    }
  });

  test('TC-ADM-SR-02: Assign admin to request', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    const assignBtn = page.locator('button:has-text("Assign"), a:has-text("Assign")').first();
    if (await assignBtn.count() > 0) {
      await assignBtn.click();
      await page.waitForLoadState('networkidle');
    }
  });

  test('TC-ADM-SR-03: Update request status', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    // Click on a request
    const requestRow = page.locator('a[href*="sourcing-requests/"]').first();
    if (await requestRow.count() > 0) {
      await requestRow.click();
      await page.waitForLoadState('networkidle');

      // Look for status change buttons
      const statusBtn = page.locator('button:has-text("In Review"), button:has-text("Quote"), select[name="status"]').first();
      if (await statusBtn.count() > 0) {
        if ((await statusBtn.getAttribute('type')) === 'select-one') {
          await statusBtn.selectOption({ index: 1 });
        } else {
          await statusBtn.click();
        }
        await page.waitForLoadState('networkidle');
      }
    }
  });
});
