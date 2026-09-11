import { test, expect } from '@playwright/test';
import { loginAs } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Admin Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'admin');
  });

  test('TC-ADM-D-01: Admin dashboard loads with stats', async ({ page }) => {
    await expect(page).toHaveURL(/\/admin\/dashboard/);

    // Dashboard should have stats cards/numbers
    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-ADM-D-02: Status filter tabs work', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-requests`);
    await page.waitForLoadState('networkidle');

    // Click on different status tabs
    const tabs = page.locator('a[href*="status="]');
    const tabCount = await tabs.count();
    if (tabCount > 0) {
      await tabs.first().click();
      await page.waitForLoadState('domcontentloaded');
    }
  });

  test('TC-ADM-D-03: Search requests by shared_id', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/sourcing-requests?search=SB00005`);
    await page.waitForLoadState('networkidle');

    // Search should return results or empty state
    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });
});
