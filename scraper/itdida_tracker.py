import sys
import json
import time
import argparse
import os
import re
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from typing import List, Dict

class OptimizedOrderTrackerSelenium:

    def __init__(self, headless: bool = True):
        self.driver = None
        self.headless = headless

    def _init_driver(self):
        options = Options()
        if self.headless:
            options.add_argument("--headless=new")
            options.add_argument("--disable-gpu")
            options.add_argument("--no-sandbox")
            options.add_argument("--disable-dev-shm-usage")
            options.add_argument("--disable-setuid-sandbox")
            options.add_argument("--remote-debugging-port=9222")
            options.add_argument("--disable-software-rasterizer")
            options.add_argument("--disable-extensions")
            options.add_argument("--ash-no-coredump")
            options.add_argument("--user-data-dir=/tmp/chrome-user-data-itdida-" + str(time.time()))
            options.add_argument("--remote-debugging-pipe")

        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_argument("--log-level=3") 
        options.add_argument("--disable-infobars")
        
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)
        options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36")

        prefs = {
            "profile.managed_default_content_settings.images": 2, 
            "profile.managed_default_content_settings.stylesheets": 2, 
        }
        options.add_experimental_option("prefs", prefs)

        chrome_binary = os.environ.get('CHROME_BINARY_PATH')
        if chrome_binary and os.path.exists(chrome_binary):
            options.binary_location = chrome_binary

        driver_path = os.environ.get('CHROMEDRIVER_PATH')
        if driver_path and os.path.exists(driver_path):
            service = Service(executable_path=driver_path)
            self.driver = webdriver.Chrome(service=service, options=options)
        else:
            self.driver = webdriver.Chrome(options=options)

        self.driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")
        try:
            self.driver.execute_cdp_cmd("Network.enable", {})
            self.driver.execute_cdp_cmd(
                "Network.setBlockedURLs",
                {
                    "urls": [
                        "*.css",
                        "*.woff",
                        "*.woff2",
                        "*.ttf",
                        "*.otf",
                        "*.png",
                        "*.jpg",
                        "*.jpeg",
                        "*.gif",
                        "*.webp",
                        "*.svg",
                    ]
                },
            )
        except Exception:
            pass

    def get_order_status(self, tracking_number: str) -> Dict:
        if self.driver is None:
            self._init_driver()

        base_url = "https://ydl.itdida.com/query.xhtml"
        try:
            self.driver.get(f"{base_url}?danHao={tracking_number}")
            wait = WebDriverWait(self.driver, 15)
            wait.until(lambda driver: len(driver.find_elements(By.TAG_NAME, "table")) > 0)

            # 1. Expand all rows to see detailed history
            togglers = self.driver.find_elements(By.CLASS_NAME, "ui-row-toggler")
            for t in togglers:
                try:
                    self.driver.execute_script("arguments[0].click();", t)
                except:
                    pass

            # 2. Extract events from ALL tables
            tables = self.driver.find_elements(By.TAG_NAME, "table")
            
            events = []
            seen_entries = set()
            
            # Pattern for Date/Time: 2025-11-22 23:00:00
            ts_pattern = re.compile(r'(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}:\d{2})')

            for table in tables:
                rows = table.find_elements(By.TAG_NAME, "tr")
                for row in rows:
                    text = row.text.strip()
                    ts_match = ts_pattern.search(text)
                    
                    if ts_match:
                        cells = row.find_elements(By.TAG_NAME, "td")
                        
                        full_ts = f"{ts_match.group(1)} {ts_match.group(2)}"
                        
                        # Extracting status and location from cell values
                        if len(cells) >= 3:
                            # Heuristic for ITDIDA tables:
                            # Index | Date | Time | Status | Location
                            # OR
                            # UserRef | Date | Time | Status | Dept | Qty | City
                            
                            row_vals = [c.text.strip() for c in cells if c.text.strip()]
                            
                            # Find indices
                            date_idx = -1
                            for i, v in enumerate(row_vals):
                                if ts_match.group(1) in v:
                                    date_idx = i
                                    break
                            
                            if date_idx != -1:
                                # Status is usually after Date/Time
                                # If Time is in the same cell as Date or Status, we skip it
                                # But let's look at the cells relative to date_idx
                                
                                # In the sub-table (history):
                                # 0: Index, 1: DateTime (merged), 2: Status, 3: Location
                                # In the main table:
                                # 1: Ref, 2: Date, 3: Time Status (merged), 4: Dept...
                                
                                if len(row_vals) > date_idx + 1:
                                    potential_status = row_vals[date_idx + 1]
                                    # If time was in the same cell as status, regex it again
                                    s_match = re.search(r'\d{2}:\d{2}:\d{2}\s*(.*)', potential_status)
                                    status = s_match.group(1).strip() if s_match else potential_status
                                    
                                    location = row_vals[date_idx + 2] if len(row_vals) > date_idx + 2 else ""
                                else:
                                    status = "Inconnu"
                                    location = ""
                            else:
                                status = "Inconnu"
                                location = ""
                        else:
                            status = "Inconnu"
                            location = ""

                        # If status is just numbers or empty, try another way
                        if not status or status.isdigit() or status == tracking_number:
                             # Fallback to splitting the whole text
                             remaining = text.replace(ts_match.group(0), "").replace(tracking_number, "").strip()
                             parts = remaining.split()
                             status = parts[0] if parts else "Inconnu"
                             location = " ".join(parts[1:]) if len(parts) > 1 else ""

                        # deduplication
                        entry_key = f"{full_ts}|{status}"
                        if entry_key not in seen_entries:
                            events.append({
                                "date": full_ts,
                                "status": status,
                                "location_raw": location,
                                "reference": tracking_number
                            })
                            seen_entries.add(entry_key)

            # Sort events by date (descending)
            events.sort(key=lambda x: x['date'], reverse=True)

            if not events:
                body_text = self.driver.find_element(By.TAG_NAME, "body").text
                if "No records found" in body_text or "未找到" in body_text:
                    return {"success": False, "tracking_number": tracking_number, "error": "Numéro introuvable."}
                
                return {
                    "success": False,
                    "tracking_number": tracking_number,
                    "error": "Aucune donnée trouvée après extraction.",
                }

            return {
                "success": True,
                "tracking_number": tracking_number,
                "events": events
            }

        except Exception as e:
            return {
                "success": False,
                "tracking_number": tracking_number,
                "error": str(e)
            }

    def close(self):
        if self.driver:
            self.driver.quit()
            self.driver = None

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument('tracking_numbers', nargs='+')
    args = parser.parse_args()
    tracker = OptimizedOrderTrackerSelenium(headless=True)
    try:
        if args.tracking_numbers:
            result = tracker.get_order_status(args.tracking_numbers[0])
            print(json.dumps(result, ensure_ascii=False))
        else:
            print(json.dumps({"success": False, "error": "No tracking number"}))
    except Exception as e:
        print(json.dumps({"success": False, "error": str(e)}, ensure_ascii=False))
    finally:
        tracker.close()