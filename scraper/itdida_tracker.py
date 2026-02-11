import sys
import json
import time
import argparse
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from typing import List, Dict

# STATUS_MAP and deep_translator REMOVED - Logic moved to PHP Service

class OptimizedOrderTrackerSelenium:

    def __init__(self, headless: bool = True):
        self.driver = None
        self.headless = headless

    def _init_driver(self):
        chrome_options = Options()
        if self.headless:
            chrome_options.add_argument("--headless=new")

        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")
        chrome_options.add_argument("--disable-blink-features=AutomationControlled")
        chrome_options.add_argument("--log-level=3") # Suppress logging
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--disable-extensions")
        chrome_options.add_argument("--disable-infobars")
        
        prefs = {
            "profile.managed_default_content_settings.images": 2, 
            "profile.managed_default_content_settings.stylesheets": 2, 
        }
        chrome_options.add_experimental_option("prefs", prefs)

        chrome_options.add_experimental_option("excludeSwitches", ["enable-automation"])
        chrome_options.add_experimental_option("useAutomationExtension", False)

        # Proxy Configuration
        http_proxy = os.environ.get('HTTP_PROXY') or os.environ.get('http_proxy')
        https_proxy = os.environ.get('HTTPS_PROXY') or os.environ.get('https_proxy')

        if http_proxy or https_proxy:
            # Prefer HTTPS proxy, fallback to HTTP
            proxy_url = https_proxy or http_proxy
            if proxy_url:
                chrome_options.add_argument(f'--proxy-server={proxy_url}')

        chrome_binary = os.environ.get('CHROME_BINARY_PATH')
        if chrome_binary:
            chrome_options.binary_location = chrome_binary

        # Priority: CHROMEDRIVER_PATH > webdriver-manager > default fallback
        driver_path = os.environ.get('CHROMEDRIVER_PATH')
        if driver_path:
            service = Service(executable_path=driver_path)
        else:
            try:
                from webdriver_manager.chrome import ChromeDriverManager
                service = Service(ChromeDriverManager().install())
            except ImportError:
                # Manual fallback
                default_path = r"D:\telechargements\chromedriver-win32\chromedriver-win32\chromedriver.exe"
                service = Service(executable_path=default_path)

        self.driver = webdriver.Chrome(service=service, options=chrome_options)

        self.driver.execute_cdp_cmd(
            "Network.setUserAgentOverride",
            {
                "userAgent": (
                    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
                    "AppleWebKit/537.36 (KHTML, like Gecko) "
                    "Chrome/120.0.0.0 Safari/537.36"
                )
            }
        )

    def get_order_status(self, tracking_number: str) -> Dict:
        if self.driver is None:
            self._init_driver()

        base_url = "https://ydl.itdida.com/query.xhtml"
        try:
            self.driver.get(f"{base_url}?danHao={tracking_number}")

            try:
                WebDriverWait(self.driver, 15).until(
                    EC.presence_of_element_located((By.CSS_SELECTOR, "tbody.ui-datatable-data tr"))
                )
                
                if "No records found" in self.driver.page_source: 
                     return {"success": False, "tracking_number": tracking_number, "error": "Numéro introuvable (No records found)"}

            except Exception:
                 # Debug screenshot removed for robustness unless needed
                 return {
                     "success": False, 
                     "tracking_number": tracking_number, 
                     "error": "Timeout waiting for results.",
                 }

            events = self._extract_tracking_data()

            if not events:
                return {
                    "success": False,
                    "tracking_number": tracking_number,
                    "error": "Aucune donnée trouvée après extraction.",
                }

            # PHP will handle translation and current status extraction
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

    def _extract_tracking_data(self) -> List[Dict]:
        events = []
        try:
            rows = self.driver.find_elements(By.CSS_SELECTOR, "tbody.ui-datatable-data tr")

            for row in rows:
                cells = row.find_elements(By.TAG_NAME, "td")
                
                if len(cells) < 4:
                    continue

                status_cn = cells[3].text.strip()
                
                location_cn = ""
                if len(cells) >= 5:
                    location_cn = cells[4].text.strip()

                # Translation Logic REMOVED - Raw data returned

                event = {
                    "location_raw": location_cn,
                    "step": cells[0].text.strip(),
                    "reference": cells[1].text.strip(),
                    "date": cells[2].text.strip(),
                    "status": status_cn
                }

                events.append(event)
        except Exception:
            pass

        return events

    def close(self):
        if self.driver:
            self.driver.quit()
            self.driver = None

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Track ITDIDA shipment.')
    parser.add_argument('tracking_numbers', nargs='+', help='One or more tracking numbers')
    args = parser.parse_args()

    tracker = OptimizedOrderTrackerSelenium(headless=True)
    results = []
    
    try:
        for tn in args.tracking_numbers:
            result = tracker.get_order_status(tn)
            results.append(result)
        
        print(json.dumps(results, ensure_ascii=False))

    except Exception as e:
        error_res = {"success": False, "error": str(e)}
        print(json.dumps(error_res, ensure_ascii=False))
    finally:
        tracker.close()