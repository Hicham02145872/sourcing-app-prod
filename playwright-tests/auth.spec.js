
import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
  const client_email = 'hichamaltit91@gmail.com';
  const client_pass = 'Hicham2334';
  const admin_email = 'hicham.altit2004@gmail.com';
  const admin_pass = 'Hicham2334';

  test('allows a client to log in', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', client_email);
    await page.fill('input[name="password"]', client_pass);
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL('/client/dashboard');
    await expect(page.locator('header h2')).toContainText('Dashboard');
  });

  test('allows an admin to log in', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', admin_email);
    await page.fill('input[name="password"]', admin_pass);
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL('/admin/dashboard');
    await expect(page.locator('header h2')).toContainText('Dashboard');
  });
});
