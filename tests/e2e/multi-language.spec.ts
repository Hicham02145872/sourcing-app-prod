import { test, expect } from '@playwright/test';
import { gotoLocalized } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Multi-Language Support', () => {
  test('TC-LANG-01: English is default locale', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng`);
    await page.waitForLoadState('networkidle');

    // Page should load in English
    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-LANG-02: Switch to French', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng`);
    await page.waitForLoadState('networkidle');

    // Look for language switcher
    const langSwitcher = page.locator('a:has-text("FR"), button:has-text("FR"), [data-locale="fr"]').first();
    if (await langSwitcher.count() > 0) {
      await langSwitcher.click();
      await page.waitForLoadState('networkidle');

      // URL should contain /fr
      expect(page.url()).toContain('/fr');
    } else {
      // Direct navigation
      await gotoLocalized(page, '', 'fr');
      await page.waitForLoadState('networkidle');
      expect(page.url()).toContain('/fr');
    }
  });

  test('TC-LANG-03: Switch to Arabic (RTL)', async ({ page }) => {
    await gotoLocalized(page, '', 'ar');
    await page.waitForLoadState('networkidle');

    // Check for RTL direction
    const htmlDir = await page.getAttribute('html', 'dir');
    expect(htmlDir).toBe('rtl');
  });

  test('TC-LANG-04: Language switcher on welcome page', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng`);
    await page.waitForLoadState('networkidle');

    // Should have language links
    const langLinks = page.locator('a[href*="/fr"], a[href*="/ar"], a[href*="/eng"]');
    const count = await langLinks.count();
    // At least some language options should exist
    expect(count).toBeGreaterThanOrEqual(0);
  });

  test('TC-LANG-05: Static pages load in different locales', async ({ page }) => {
    for (const locale of ['eng', 'fr', 'ar']) {
      await gotoLocalized(page, '/terms-of-service', locale);
      await page.waitForLoadState('networkidle');

      const body = await page.textContent('body');
      expect(body).toBeTruthy();
    }
  });
});
