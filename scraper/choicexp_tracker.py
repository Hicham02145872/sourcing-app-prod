import sys
import json
import time
import argparse
import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class ChoiceXPTracker:
    def __init__(self, headless=True):
        self.headless = headless
        self.driver = None

    def _init_driver(self):
        options = webdriver.ChromeOptions()
        if self.headless:
            options.add_argument("--headless=new")
            options.add_argument("--disable-gpu")
            options.add_argument("--no-sandbox")
            options.add_argument("--disable-dev-shm-usage")
            options.add_argument("--disable-setuid-sandbox")
            options.add_argument("--disable-software-rasterizer")
            options.add_argument("--remote-debugging-pipe")
            options.add_argument("--disable-extensions")
            options.add_argument("--ash-no-coredump")
            options.add_argument("--user-data-dir=/tmp/chrome-choicexp")
            options.add_argument("--remote-debugging-pipe")
        
        # Anti-detection options
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option('useAutomationExtension', False)
        options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36")

        # Proxy Configuration
        http_proxy = os.environ.get('HTTP_PROXY') or os.environ.get('http_proxy')
        https_proxy = os.environ.get('HTTPS_PROXY') or os.environ.get('https_proxy')

        if http_proxy or https_proxy:
            # Prefer HTTPS proxy, fallback to HTTP
            proxy_url = https_proxy or http_proxy
            if proxy_url:
                options.add_argument(f'--proxy-server={proxy_url}')

        chrome_binary = os.environ.get('CHROME_BINARY_PATH')
        if chrome_binary and os.path.exists(chrome_binary):
            options.binary_location = chrome_binary
        
        # Priority: CHROMEDRIVER_PATH > Selenium Manager fallback
        driver_path = os.environ.get('CHROMEDRIVER_PATH')
        if driver_path and os.path.exists(driver_path):
            service = Service(executable_path=driver_path)
            self.driver = webdriver.Chrome(service=service, options=options)
        else:
            self.driver = webdriver.Chrome(options=options)
        
        # Hide automation flag property
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

    def scrape(self, tracking_number):
        if not self.driver:
            self._init_driver()

        url = "https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp?language=english"
        
        try:
            self.driver.get(url)
            wait = WebDriverWait(self.driver, 20)
            
            input_field = wait.until(EC.presence_of_element_located((By.ID, "hbl_search")))
            input_field.clear()
            input_field.send_keys(tracking_number)
            
            search_btn = self.driver.find_element(By.ID, "searchBtn")
            search_btn.click()
            wait.until(EC.presence_of_element_located((By.CLASS_NAME, "cd-timeline-block")))
            
            blocks = self.driver.find_elements(By.CLASS_NAME, "cd-timeline-block")
            events = []
            for block in blocks:
                try:
                    date = block.find_element(By.CLASS_NAME, "cd-timeline-date").text
                    info = block.find_element(By.CLASS_NAME, "cd-timeline-content").text.strip()
                    
                    location = ""
                    if " at " in info.lower():
                        parts = info.lower().split(" at ")
                        if len(parts) > 1:
                            loc_candidate = parts[1].split('.')[0].strip().title()
                            if len(loc_candidate) < 30: # sanity check
                                location = loc_candidate
                    elif " in " in info.lower():
                         parts = info.lower().split(" in ")
                         if len(parts) > 1:
                            loc_candidate = parts[1].split('.')[0].strip().title()
                            if len(loc_candidate) < 30:
                                location = loc_candidate
                    
                    events.append({
                        "date": date,
                        "status": info,
                        "location": location
                    })
                except:
                    continue
            
            if not events:
                return {"success": False, "error": "No tracking data found."}
                
            return {
                "success": True,
                "tracking_number": tracking_number,
                "current_status": events[0]["status"] if events else "Unknown",
                "events": events
            }

        except Exception as e:
            return {"success": False, "error": str(e)}

    def close(self):
        if self.driver:
            try:
                self.driver.quit()
            except Exception:
                pass
            self.driver = None

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="ChoiceXP Scraper")
    parser.add_argument("tracking_number", help="The tracking number")
    args = parser.parse_args()

    tracker = ChoiceXPTracker(headless=True)
    try:
        result = tracker.scrape(args.tracking_number)
        print(json.dumps(result, ensure_ascii=False))
    finally:
        tracker.close()
