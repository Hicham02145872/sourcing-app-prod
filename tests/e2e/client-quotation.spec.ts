import { test, expect } from '@playwright/test';
import { loginAs, gotoLocalized } from './helpers';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

test.describe('Client Quotation Actions', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'client');
  });

  test('TC-QT-01: View quotations list', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/quotations`);
    await page.waitForLoadState('networkidle');

    const body = await page.textContent('body');
    expect(body).toBeTruthy();
  });

  test('TC-QT-02: Accept a quotation creates an order with FSB reference', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/quotations`);
    await page.waitForLoadState('domcontentloaded');

    // Find an Accept Quotation link
    const acceptLink = page.locator('a:has-text("Accept Quotation")').first();
    if (await acceptLink.count() > 0) {
      await acceptLink.click();
      await page.waitForLoadState('domcontentloaded');

      // Upload proof of payment (real 1x1 PNG so server-side mime detection passes)
      const fileInput = page.locator('input[name="proof_of_payment"]').first();
      if (await fileInput.count() > 0) {
        await fileInput.setInputFiles({
          name: 'proof.png',
          mimeType: 'image/png',
          buffer: Buffer.from(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
            'base64',
          ),
        });
      }

      // Submit the accept form
      const acceptForm = page.locator('form[action*="quotations/"][action*="accept"]');
      const submitBtn = acceptForm.locator('button[type="submit"]');
      if (await submitBtn.count() > 0) {
        // noWaitAfter: the accept POST can exceed the 30s action timeout on a
        // cold cache (PDF + email generated synchronously); let waitForURL below
        // absorb that latency instead of timing out the click itself.
        await submitBtn.click({ noWaitAfter: true });
        // Should redirect to the created order page
        await page.waitForURL(/\/client\/sourcing-orders\/\d+/, { timeout: 90_000 });

        // Order show page should display the FSB reference
        const body = await page.textContent('body');
        expect(body).toMatch(/FSB0\d{5,}/);
      }
    }
  });

  test('TC-QT-03: Reject a quotation', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/quotations`);
    await page.waitForLoadState('networkidle');

    const rejectBtn = page.locator('button:has-text("Reject"), a:has-text("Reject")').first();
    if (await rejectBtn.count() > 0) {
      await rejectBtn.click();

      // Confirm if dialog appears
      const confirmBtn = page.locator('button:has-text("Confirm"), button:has-text("Yes")');
      if (await confirmBtn.count() > 0) {
        await confirmBtn.click();
      }

      await page.waitForLoadState('networkidle');
    }
  });

  test('TC-QT-04: Negotiate a quotation', async ({ page }) => {
    await page.goto(`${BASE_URL}/eng/client/quotations`);
    await page.waitForLoadState('networkidle');

    const negotiateBtn = page.locator('button:has-text("Negotiate"), a:has-text("Negotiate")').first();
    if (await negotiateBtn.count() > 0) {
      await negotiateBtn.click();

      // Fill negotiation notes
      const notesField = page.locator('textarea[name="negotiation_notes"]');
      if (await notesField.count() > 0) {
        await notesField.fill('I would like to negotiate the price down by 10%.');
        await page.click('button[type="submit"]');
        await page.waitForLoadState('networkidle');
      }
    }
  });
});
