import { chromium } from '@playwright/test';
import fs from 'fs';
import path from 'path';

const [url, viewportKey, outputDir] = process.argv.slice(2);

const viewports = {
    desktop: { width: 1440, height: 900 },
    tablet: { width: 768, height: 1024 },
    mobile: { width: 390, height: 844 },
};

const viewport = viewports[viewportKey] ?? viewports.desktop;

fs.mkdirSync(outputDir, { recursive: true });

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport, deviceScaleFactor: 1 });
const page = await context.newPage();

const consoleMessages = [];
const failedRequests = [];

page.on('console', (msg) => {
    if (msg.type() === 'error' || msg.type() === 'warning') {
        consoleMessages.push({ type: msg.type(), text: msg.text().slice(0, 500) });
    }
});

page.on('requestfailed', (req) => {
    failedRequests.push({
        url: req.url().slice(0, 300),
        error: req.failure()?.errorText ?? 'unknown',
    });
});

let response = null;
let navigationError = null;

try {
    response = await page.goto(url, { waitUntil: 'networkidle', timeout: 45000 });
} catch (err) {
    navigationError = err.message.split('\n')[0];
}

await page.waitForTimeout(1200);

const screenshotPath = path.join(outputDir, `screenshot-${viewportKey}.png`);
await page.screenshot({ path: screenshotPath, fullPage: true });

const pageData = await page.evaluate(() => {
    const visible = (el) => {
        const r = el.getBoundingClientRect();
        return r.width > 0 && r.height > 0;
    };

    const linkCount = Array.from(document.querySelectorAll('a')).filter(visible).length;
    const buttons = Array.from(document.querySelectorAll('button, [role="button"]')).filter(visible);
    const buttonLabels = buttons
        .map((b) => (b.innerText ?? '').trim())
        .filter((t) => t.length > 0)
        .slice(0, 40);

    const headingInfo = Array.from(document.querySelectorAll('h1, h2, h3, h4'))
        .filter(visible)
        .slice(0, 30)
        .map((h) => ({ tag: h.tagName, text: (h.innerText ?? '').trim().slice(0, 100) }));

    const images = Array.from(document.querySelectorAll('img'))
        .filter(visible)
        .map((img) => ({ src: (img.currentSrc || img.src || '').slice(0, 200), alt: (img.alt ?? '').slice(0, 120) }))
        .slice(0, 40);

    const interactive = Array.from(document.querySelectorAll('a, button, input, select, textarea, [role="button"]'))
        .filter(visible);
    const noLabelInputs = Array.from(document.querySelectorAll('input, select, textarea'))
        .filter((el) => {
            if (!visible(el)) {
                return false;
            }
            const id = el.id;
            const labelledBy = el.getAttribute('aria-labelledby');
            const label = id ? document.querySelector(`label[for="${id}"]`) : null;
            return !el.getAttribute('aria-label') && !labelledBy && !label && !el.getAttribute('placeholder') === false && (el.getAttribute('type') !== 'hidden');
        })
        .map((el) => el.getAttribute('type') || el.tagName);

    const formElements = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"], textarea, select');

    return {
        title: document.title,
        lang: document.documentElement.lang || null,
        metaDescription: document.querySelector('meta[name="description"]')?.getAttribute('content') ?? null,
        fullHeight: document.documentElement.scrollHeight,
        bodyTextLength: (document.body.innerText ?? '').length,
        visibleLinks: linkCount,
        buttonLabels,
        headings: headingInfo,
        images,
        imagesWithoutAlt: images.filter((img) => !img.alt).length,
        interactiveElements: interactive.length,
        unlabeledInputs: noLabelInputs,
        formFields: formElements.length,
        inputFontSizeIssue: Array.from(formElements).filter((el) => {
            const fs = parseFloat(getComputedStyle(el).fontSize);
            return fs > 0 && fs < 16;
        }).length,
    };
});

await browser.close();

const meta = {
    url,
    viewportKey,
    viewport,
    status: response ? response.status() : null,
    navigationError,
    screenshot: path.basename(screenshotPath),
    page: pageData,
    consoleMessages,
    failedRequests,
};

process.stdout.write(JSON.stringify(meta, null, 2));
