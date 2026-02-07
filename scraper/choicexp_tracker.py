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
            options.add_argument("--headless")
            options.add_argument("--disable-gpu")
            options.add_argument("--no-sandbox")
            options.add_argument("--disable-dev-shm-usage")
        
        # Anti-detection options
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option('useAutomationExtension', False)
        options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36")

        chrome_binary = os.environ.get('CHROME_BINARY_PATH')
        if chrome_binary:
            options.binary_location = chrome_binary

        # Priority: CHROMEDRIVER_PATH > webdriver-manager > default fallback
        driver_path = os.environ.get('CHROMEDRIVER_PATH')
        if driver_path:
            service = Service(executable_path=driver_path)
            self.driver = webdriver.Chrome(service=service, options=options)
        else:
            try:
                from webdriver_manager.chrome import ChromeDriverManager
                self.driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
            except ImportError:
                self.driver = webdriver.Chrome(options=options)
        
        # Hide automation flag property
        self.driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")

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
            
            time.sleep(2)
            
            wait.until(EC.presence_of_element_located((By.CLASS_NAME, "cd-timeline-block")))
            
            blocks = self.driver.find_elements(By.CLASS_NAME, "cd-timeline-block")
            events = []
            
            for block in blocks:
                try:
                    date = block.find_element(By.CLASS_NAME, "cd-timeline-date").text
                    info = block.find_element(By.CLASS_NAME, "cd-timeline-content").text
                    events.append({
                        "date": date,
                        "status": info,
                        "location": "" # ChoiceXP might not separate location
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
        finally:
            if self.driver:
                self.driver.quit()

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="ChoiceXP Scraper")
    parser.add_argument("tracking_number", help="The tracking number")
    args = parser.parse_args()
    
    tracker = ChoiceXPTracker(headless=True)
    result = tracker.scrape(args.tracking_number)
    print(json.dumps(result, ensure_ascii=False))
