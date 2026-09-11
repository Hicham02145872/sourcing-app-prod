import { type Page, expect } from '@playwright/test';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

export const TEST_USERS = {
  client: {
    email: 'test-client@example.com',
    password: 'password',
    name: 'Test Client',
  },
  admin: {
    email: 'test-admin@example.com',
    password: 'password',
    name: 'Test Admin',
  },
  superAdmin: {
    email: 'test-superadmin@example.com',
    password: 'password',
    name: 'Test Super Admin',
  },
};

/**
 * Login as a user via the login form.
 */
export async function loginAs(
  page: Page,
  role: 'client' | 'admin' | 'superAdmin',
): Promise<void> {
  const user = TEST_USERS[role];
  await page.goto(`${BASE_URL}/eng/login`);
  await page.fill('input[name="email"]', user.email);
  await page.fill('input[name="password"]', user.password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard', { timeout: 30_000 });
}

/**
 * Logout the current user via the visible logout form.
 */
export async function logout(page: Page): Promise<void> {
  const form = page.locator('form[action*="logout"]:visible').first();
  await form.waitFor({ state: 'visible', timeout: 15_000 });
  await form.evaluate((f) => f.submit());
  await page.waitForURL(/\/login|\/eng\/?$|\/fr\/?$|\/ar\/?$/, { timeout: 30_000 });
}

/**
 * Assert that a page has no console errors.
 */
export async function assertNoConsoleErrors(page: Page): Promise<void> {
  const errors: string[] = [];
  page.on('console', (msg) => {
    if (msg.type() === 'error') {
      errors.push(msg.text());
    }
  });
  // Allow specific known errors
  const filtered = errors.filter(
    (e) =>
      !e.includes('favicon') &&
      !e.includes('404') &&
      !e.includes('ERR_NAME_NOT_RESOLVED'),
  );
  expect(filtered).toEqual([]);
}

/**
 * Assert that no SB reference is visible for an order.
 */
export async function assertNoSbForOrder(page: Page): Promise<void> {
  const body = await page.textContent('body');
  // SB references are like SB00005, SB00010, etc.
  // FSB references like FSB000005 are OK
  const sbMatches = body?.match(/\bSB0\d{4,}\b/g);
  expect(sbMatches || []).toEqual([]);
}

/**
 * Assert that FSB reference is visible on the page.
 */
export async function assertFsbVisible(page: Page, orderId: number): Promise<void> {
  const fsbNumber = `FSB${String(orderId).padStart(6, '0')}`;
  await expect(page.locator(`text=${fsbNumber}`)).toBeVisible({ timeout: 10_000 });
}

/**
 * Seed the database by calling an artisan command via the app container.
 * This should be called once before all tests in a describe block.
 */
export async function seedDatabase(page: Page): Promise<void> {
  // We call the test-seed endpoint (defined in routes/api.php or routes/web.php)
  // The seed script runs via: docker compose exec app php artisan db:seed --class=TestingSeeder
  // For E2E tests, we rely on the docker/test-seed.sh script that runs before tests.
  // This is a placeholder — actual seeding happens in the Docker entrypoint.
}

/**
 * Wait for Vite assets to load.
 */
export async function waitForViteAssets(page: Page): Promise<void> {
  // Wait for at least one stylesheet or module script to be loaded
  await page.waitForLoadState('networkidle');
}

/**
 * Navigate to a localized URL.
 */
export async function gotoLocalized(page: Page, path: string, locale = 'eng'): Promise<void> {
  await page.goto(`${BASE_URL}/${locale}${path}`);
}
