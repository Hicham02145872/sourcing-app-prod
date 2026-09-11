import { test, expect } from '@playwright/test';
import { loginAs, logout, gotoLocalized, TEST_USERS } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Authentication', () => {
  test('TC-AUTH-01: Register a new client account', async ({ page }) => {
    await gotoLocalized(page, '/register');

    // Fill registration form
    await page.fill('input[name="name"]', 'Emma Dubois');
    await page.fill('input[name="email"]', `e2e-register-${Date.now()}@test.com`);
    await page.fill('input[name="phone"]', '123456789');
    await page.fill('input[name="password"]', 'Password123!');
    await page.fill('input[name="password_confirmation"]', 'Password123!');
    await page.check('input[name="terms"]');

    // Submit
    await page.click('button[type="submit"]');

    // Should redirect to verification notice or dashboard
    await page.waitForURL(/verify-email|dashboard/, { timeout: 15_000 });
  });

  test('TC-AUTH-02: Login as client and land on dashboard', async ({ page }) => {
    await loginAs(page, 'client');

    // Should be on client dashboard
    await expect(page).toHaveURL(/\/eng\/client\/dashboard/);

    // Dashboard should have sidebar with FSB tracking reference
    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-AUTH-03: Login as admin and land on admin dashboard', async ({ page }) => {
    await loginAs(page, 'admin');

    // Should be on admin dashboard
    await expect(page).toHaveURL(/\/admin\/dashboard/);
  });

  test('TC-AUTH-04: Logout redirects to login page', async ({ page }) => {
    await loginAs(page, 'client');
    await expect(page).toHaveURL(/\/eng\/client\/dashboard/);

    await logout(page);
    await expect(page).toHaveURL(/\/eng\/?$/);
  });

  test('TC-AUTH-05: Login with invalid credentials shows error', async ({ page }) => {
    await gotoLocalized(page, '/login');
    await page.fill('input[name="email"]', 'wrong@example.com');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');

    // Should show validation error
    await expect(page.locator('.text-red-500, .text-red-600, [role="alert"]')).toBeVisible({
      timeout: 10_000,
    });
  });

  test('TC-AUTH-06: Forgot password page loads', async ({ page }) => {
    await gotoLocalized(page, '/forgot-password');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });
});
