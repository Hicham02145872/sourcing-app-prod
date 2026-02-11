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
        
        # Hide automation flag property
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)

        prefs = {
            "profile.managed_default_content_settings.images": 2, 
            "profile.managed_default_content_settings.stylesheets": 2, 
        }
        options.add_experimental_option("prefs", prefs)

        # Proxy Configuration
        http_proxy = os.environ.get('HTTP_PROXY') or os.environ.get('http_proxy')
        https_proxy = os.environ.get('HTTPS_PROXY') or os.environ.get('https_proxy')

        if http_proxy or https_proxy:
            proxy_url = https_proxy or http_proxy
            if proxy_url:
                options.add_argument(f'--proxy-server={proxy_url}')

        chrome_binary = os.environ.get('CHROME_BINARY_PATH')
        if chrome_binary and os.path.exists(chrome_binary):
            options.binary_location = chrome_binary

        # Priority: CHROMEDRIVER_PATH > webdriver-manager > default fallback
        driver_path = os.environ.get('CHROMEDRIVER_PATH')
        if driver_path and os.path.exists(driver_path):
            service = Service(executable_path=driver_path)
            self.driver = webdriver.Chrome(service=service, options=options)
        else:
            try:
                from webdriver_manager.chrome import ChromeDriverManager
                driver_install_path = ChromeDriverManager().install()
                self.driver = webdriver.Chrome(service=Service(executable_path=driver_install_path), options=options)
            except Exception:
                # Generic fallback for Linux (assuming it's in PATH)
                self.driver = webdriver.Chrome(options=options)

        self.driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")

    def get_order_status(self, tracking_number: str) -> Dict:
        if self.driver is None:
            self._init_driver()

        base_url = "https://ydl.itdida.com/query.xhtml"
        try:
            self.driver.get(f"{base_url}?danHao={tracking_number}")

            try:
                # Increased timeout to 25s
                WebDriverWait(self.driver, 25).until(
                    EC.presence_of_element_located((By.CSS_SELECTOR, "tbody.ui-datatable-data tr"))
                )
                
                if "No records found" in self.driver.page_source: 
                     return {"success": False, "tracking_number": tracking_number, "error": "Numéro introuvable (No records found)"}

            except Exception:
                 # Check if the page at least loaded
                 if "danHao" not in self.driver.current_url:
                     return {"success": False, "error": "Erreur de chargement de la page ITDIDA."}
                 
                 return {
                     "success": False, 
                     "tracking_number": tracking_number, 
                     "error": "Timeout waiting for results (ITDIDA).",
                 }

            events = self._extract_tracking_data()

            if not events:
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

    def _extract_tracking_data(self) -> List[Dict]:
        events = []
        try:
            rows = self.driver.find_elements(By.CSS_SELECTOR, "tbody.ui-datatable-data tr")
            if not rows:
                rows = self.driver.find_elements(By.TAG_NAME, "tr")

            for row in rows:
                cells = row.find_elements(By.TAG_NAME, "td")
                
                if len(cells) < 4:
                    continue

                status_cn = cells[3].text.strip()
                
                location_cn = ""
                if len(cells) >= 5:
                    location_cn = cells[4].text.strip()

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
    try:
        if args.tracking_numbers:
            result = tracker.get_order_status(args.tracking_numbers[0])
            # Ensure we print valid JSON
            print(json.dumps(result, ensure_ascii=False))
        else:
            print(json.dumps({"success": False, "error": "No tracking number provided"}))
    except Exception as e:
        print(json.dumps({"success": False, "error": str(e)}, ensure_ascii=False))
    finally:
        tracker.close()