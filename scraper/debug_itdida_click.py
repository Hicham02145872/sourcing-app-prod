import sys
import json
import time
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service

def analyze_ydl(tracking_number):
    options = Options()
    options.add_argument("--headless=new")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.add_argument("--disable-blink-features=AutomationControlled")
    
    driver = webdriver.Chrome(options=options)
    
    try:
        url = f"https://ydl.itdida.com/query.xhtml?danHao={tracking_number}"
        print(f"--- Clicking Analysis : {url} ---")
        driver.get(url)
        time.sleep(10)

        # 1. Look for row togglers or dynamic buttons
        togglers = driver.find_elements(By.CLASS_NAME, "ui-row-toggler")
        print(f"Row togglers found: {len(togglers)}")
        for i, t in enumerate(togglers):
            print(f"  Clicking toggler {i}...")
            driver.execute_script("arguments[0].click();", t)
            time.sleep(2)

        # 2. Look for "Detail" or "View" buttons
        buttons = driver.find_elements(By.TAG_NAME, "button")
        for b in buttons:
            if b.is_displayed():
                print(f"  Button found: {b.text}")

        # 3. Analyze tables again after clicks
        tables = driver.find_elements(By.TAG_NAME, "table")
        for tidx, table in enumerate(tables):
            print(f"\nTable {tidx} after clicks:")
            rows = table.find_elements(By.TAG_NAME, "tr")
            for ridx, row in enumerate(rows):
                print(f"  Row {ridx}: {row.text}")

    except Exception as e:
        print(f"Error: {e}")
    finally:
        driver.quit()

if __name__ == "__main__":
    analyze_ydl(sys.argv[1] if len(sys.argv) > 1 else "K0121112B")
