"""
UPS tracking scraper via Selenium.
Outputs a single JSON object to stdout for consumption by UPSTrackingService.

Class UPSTracker: persistent driver mode (used by tracking_server.py).
Function get_ups_status(): one-shot mode (used by __main__ / legacy CLI).
"""
import json
import re
import argparse
import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait


def _build_chrome_options(headless=True):
    """Build Chrome options consistent with other scrapers (itdida, choicexp)."""
    options = Options()
    if headless:
        options.add_argument("--headless=new")
    options.add_argument("--disable-gpu")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.add_argument("--disable-setuid-sandbox")
    options.add_argument("--disable-software-rasterizer")
    options.add_argument("--disable-extensions")
    options.add_argument("--disable-blink-features=AutomationControlled")
    options.add_argument("--log-level=3")
    options.add_argument("--user-data-dir=/tmp/chrome-ups")
    options.add_argument(
        "user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
        "AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    )
    options.add_experimental_option("excludeSwitches", ["enable-automation"])
    options.add_experimental_option("useAutomationExtension", False)

    http_proxy = os.environ.get("HTTP_PROXY") or os.environ.get("http_proxy")
    https_proxy = os.environ.get("HTTPS_PROXY") or os.environ.get("https_proxy")
    if http_proxy or https_proxy:
        proxy_url = https_proxy or http_proxy
        if proxy_url:
            options.add_argument(f"--proxy-server={proxy_url}")

    chrome_binary = os.environ.get("CHROME_BINARY_PATH")
    if chrome_binary and os.path.exists(chrome_binary):
        options.binary_location = chrome_binary

    return options


def _create_driver(headless=True):
    options = _build_chrome_options(headless=headless)
    driver_path = os.environ.get("CHROMEDRIVER_PATH")
    if driver_path and os.path.exists(driver_path):
        service = Service(executable_path=driver_path)
        driver = webdriver.Chrome(service=service, options=options)
    else:
        driver = webdriver.Chrome(options=options)

    driver.execute_script(
        "Object.defineProperty(navigator, 'webdriver', {get: () => undefined})"
    )
    try:
        driver.execute_cdp_cmd("Network.enable", {})
        driver.execute_cdp_cmd(
            "Network.setBlockedURLs",
            {
                "urls": [
                    "*.css", "*.woff", "*.woff2", "*.ttf", "*.otf",
                    "*.png", "*.jpg", "*.jpeg", "*.gif", "*.webp", "*.svg",
                ]
            },
        )
    except Exception:
        pass
    return driver


def parse_milestone_text(milestone_text):
    """
    Parse a UPS milestone line like:
      "Delivered POME, IT 01/14/2026, 11:03 A.M."
    into date (ISO-friendly for frontend), location, and status.
    """
    text = (milestone_text or "").strip()
    if not text:
        return {"date": "", "location": "", "status": text}

    date_re = re.search(
        r"(\d{1,2}/\d{1,2}/\d{4}, \d{1,2}:\d{2}\s*[AP]\.?M\.?)",
        text,
        re.IGNORECASE,
    )
    date_str = ""
    remainder = text
    if date_re:
        date_str = date_re.group(1).strip()
        remainder = text[: date_re.start()].strip()
        try:
            parts = date_str.split(",")
            if len(parts) >= 2:
                md, hm = parts[0].strip(), parts[1].strip()
                m, d, y = md.split("/")
                hm = re.sub(r"\s*[AP]\.?M\.?", "", hm, flags=re.IGNORECASE).strip()
                date_str = f"{y}-{m.zfill(2)}-{d.zfill(2)} {hm}"
        except Exception:
            pass

    location = ""
    status = remainder
    loc_match = re.search(r"\s+([^ ]+, [^ ]+)$", remainder)
    if loc_match:
        location = loc_match.group(1).strip()
        status = remainder[: loc_match.start()].strip()

    return {"date": date_str, "location": location, "status": status or remainder}


def _scrape_with_driver(driver, tracking_number):
    """Core scraping logic — reusable by both persistent and one-shot modes."""
    result = {
        "success": False,
        "tracking_number": tracking_number,
        "error": None,
        "events": [],
        "current_status": None,
    }

    try:
        url = f"https://www.ups.com/track?tracknum={tracking_number}"
        driver.get(url)
        wait = WebDriverWait(driver, 15)
        wait.until(lambda d: d.find_element(By.TAG_NAME, "body").text.strip())

        body_text = driver.find_element(By.TAG_NAME, "body").text

        if "Delivered" in body_text:
            current_status = "Delivered"
        elif "On the Way" in body_text or "In Transit" in body_text:
            current_status = "On the Way"
        elif "Out For Delivery" in body_text:
            current_status = "Out For Delivery"
        elif "Order Processed" in body_text or "Ready for Pickup" in body_text:
            current_status = "Order Processed"
        else:
            current_status = "In Transit"

        result["current_status"] = current_status

        events = []
        date_val = ""
        time_val = ""

        try:
            status_label = driver.find_element(By.ID, "st_App_DelvdLabel").text.strip()
            result["current_status"] = status_label
        except Exception:
            pass

        try:
            date_val = (
                driver.find_element(By.ID, "st_App_PkgStsMonthNum")
                .text.replace("\n", " ")
                .strip()
            )
        except Exception:
            pass
        try:
            time_val = (
                driver.find_element(By.ID, "st_App_PkgStsTime")
                .text.replace("\n", " ")
                .strip()
            )
        except Exception:
            pass

        if date_val or time_val:
            combined = f"{date_val} {time_val}".strip()
            try:
                from datetime import datetime
                dt = datetime.strptime(combined, "%b %d, %Y %I:%M %p")
                combined = dt.strftime("%Y-%m-%d %H:%M")
            except Exception:
                pass
            events.append({
                "date": combined,
                "status": result["current_status"],
                "location": "",
            })

        for i in range(10):
            try:
                elem_id = f"stApp_ShpmtProg_LVP_milestone_nameKey_{i}"
                milestone_text = (
                    driver.find_element(By.ID, elem_id).text.strip().replace("\n", " ")
                )
                if milestone_text:
                    parsed = parse_milestone_text(milestone_text)
                    events.append({
                        "date": parsed["date"],
                        "status": parsed["status"] or milestone_text,
                        "location": parsed["location"],
                    })
            except Exception:
                break

        if not events and result["current_status"]:
            events.append({
                "date": "",
                "status": result["current_status"],
                "location": "",
            })

        events.reverse()

        if events and (not events[0].get("date") or not events[0].get("location")):
            for ev in events:
                if ev.get("date") or ev.get("location"):
                    events[0]["date"] = events[0].get("date") or ev.get("date", "")
                    events[0]["location"] = events[0].get("location") or ev.get("location", "")
                    break

        result["events"] = events
        result["success"] = True
        result["error"] = None

    except Exception as e:
        result["success"] = False
        result["error"] = str(e)

    return result


class UPSTracker:
    """Persistent-driver UPS tracker for use with tracking_server.py."""

    def __init__(self, headless=True):
        self.headless = headless
        self.driver = None

    def _init_driver(self):
        self.driver = _create_driver(headless=self.headless)

    def scrape(self, tracking_number):
        if not self.driver:
            self._init_driver()
        return _scrape_with_driver(self.driver, tracking_number)

    def close(self):
        if self.driver:
            try:
                self.driver.quit()
            except Exception:
                pass
            self.driver = None


def get_ups_status(tracking_number, headless=True):
    """One-shot helper: creates a driver, scrapes, quits. Used by __main__."""
    tracker = UPSTracker(headless=headless)
    try:
        return tracker.scrape(tracking_number)
    finally:
        tracker.close()


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Get UPS status via Selenium")
    parser.add_argument(
        "tracking_number",
        nargs="?",
        default="1Z14V4W16890506495",
        help="UPS Tracking Number",
    )
    parser.add_argument("--visible", action="store_true", help="Run with visible browser")
    args = parser.parse_args()

    data = get_ups_status(args.tracking_number, headless=not args.visible)
    print(json.dumps(data, indent=None, ensure_ascii=False))
