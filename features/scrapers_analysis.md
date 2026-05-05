# Technical Analysis: Scraping Tracking Status with Crawlee

This document evaluates the feasibility of using **Crawlee** to automate the extraction of order statuses from specific logistics websites.

## 1. What is Crawlee?
**Crawlee** is a Node.js library designed for building robust web scrapers. It provides:
- **PlaywrightCrawler / PuppeteerCrawler**: Ideal for dynamic, JavaScript-heavy websites (SPA).
- **CheerioCrawler**: Fast and lightweight, but only for static HTML.
- **Anti-Blocking**: Built-in header generation, fingerprinting, and proxy rotation.
- **Persistence**: Efficient request queuing and data storage (Key-Value Store, Dataset).

---

## 2. Website Analysis & Feasibility

### Site A: J&T Express (UAE)
- **URL**: `https://www.jtexpress.me/UAE/trajectoryQuery?waybillNo=`
- **Tech Stack**: Modern SPA (Vue.js/Quasar).
- **Behavior**: Requires JavaScript to render search results.
- **Anti-Bot**: **High**. Utilizes a **Tencent Slide Captcha** ("Slide to complete the puzzle") which triggers frequently on automated visits.
- **Crawlee Feasibility**: **Possible but Complex**.
    - **Method**: Must use `PlaywrightCrawler`.
    - **Challenge**: Requires integration with a captcha solving service (e.g., 2Captcha) to simulate the slider movement.

### Site B: YDL Tracking
- **URL**: `https://ydl.itdida.com/query.xhtml?danHao=`
- **Tech Stack**: Java Server Faces (JSF).
- **Behavior**: Supports direct query via URL parameter. Results are rendered in a standard table format.
- **Anti-Bot**: **Low**. No CAPTCHAs or aggressive blocking observed during testing.
- **Crawlee Feasibility**: **Excellent**.
    - **Method**: `PlaywrightCrawler` or even `CheerioCrawler` if the initial response contains the data (though Playwright is safer for consistency).
    - **Implementation**: Navigate to the URL + tracking number, wait for the table, and extract text.

### Site C: Choice Express
- **URL**: `https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp`
- **Tech Stack**: JSP / AJAX (Jeecg Framework).
- **Behavior**: Requires interaction. The search is triggered by clicking a "Search" button after filling an input field.
- **Anti-Bot**: **Low**. No CAPTCHA visible.
- **Crawlee Feasibility**: **Very Good**.
    - **Method**: `PlaywrightCrawler`.
    - **Implementation**: Automate `page.fill('#hbl_search', number)` and `page.click('#searchBtn')`.

---

## 3. Comparison Summary

| Feature | J&T Express | YDL Tracking | Choice Express |
| :--- | :--- | :--- | :--- |
| **Complexity** | High (Captcha) | Low (Direct URL) | Medium (Interaction) |
| **Reliability** | Variable (Captcha success) | Very High | High |
| **Recommended Tool** | `PlaywrightCrawler` | `PlaywrightCrawler` | `PlaywrightCrawler` |
| **Data Format** | Dynamic UI | HTML Table | Dynamic List |

---

## 4. Implementation Strategy

To implement a unified scraper, a **`PlaywrightCrawler`** is the best all-in-one approach.

### Proposed Code Structure (Node.js)

```javascript
import { PlaywrightCrawler } from 'crawlee';

const crawler = new PlaywrightCrawler({
    // Use a real browser fingerprint to reduce blocking
    browserContextOptions: {
        viewport: { width: 1280, height: 720 },
    },
    async requestHandler({ page, request, log }) {
        const { url, userData } = request;
        
        await page.goto(url);

        if (url.includes('jtexpress')) {
            // Logic to check for and solve Slide Captcha
            log.info('Monitoring J&T Express status...');
            // Need: third-party captcha solver integration
        } 
        
        else if (url.includes('choicexp')) {
            await page.fill('#hbl_search', userData.trackingNumber);
            await page.click('#searchBtn');
            await page.waitForSelector('.mui-table-view-cell');
        }

        // Common extraction logic
        const latestStatus = await page.locator('.status-selector').first().innerText();
        log.info(`Latest status for ${userData.trackingNumber}: ${latestStatus}`);
    },
});
```

---

## 5. Conclusion
**Crawlee is the perfect tool for this task.**
- For **YDL** and **Choice Express**, development is straightforward.
- For **J&T Express**, the main investment will be captcha bypass.

> [!TIP]
> Since you are using Laravel, you can run this crawler as a scheduled **Node.js worker**. The results can then be pushed back to your database via a webhook or shared database access.
