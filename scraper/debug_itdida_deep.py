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
        print(f"--- Deep Analysis : {url} ---")
        driver.get(url)
        time.sleep(15) # Long wait for AJAX

        # 1. Catch ALL tables
        tables = driver.find_elements(By.TAG_NAME, "table")
        print(f"Total tables: {len(tables)}")
        
        for tidx, table in enumerate(tables):
            print(f"\nTable {tidx} (ID: {table.get_attribute('id')}, Class: {table.get_attribute('class')}):")
            rows = table.find_elements(By.TAG_NAME, "tr")
            for ridx, row in enumerate(rows):
                cells = row.find_elements(By.TAG_NAME, "td")
                cell_text = " | ".join([c.text.strip() for c in cells])
                print(f"  Row {ridx}: {cell_text}")

        # 2. Catch anything that looks like a row but isn't in a table
        print("\n--- Non-table potential rows (divs) ---")
        potential_rows = driver.find_elements(By.XPATH, "//*[contains(text(), '202')]")
        for i, row in enumerate(potential_rows[:20]):
            print(f"  Element {i} ({row.tag_name}): {row.text[:100]}")

    except Exception as e:
        print(f"Error: {e}")
    finally:
        driver.quit()

if __name__ == "__main__":
    analyze_ydl(sys.argv[1] if len(sys.argv) > 1 else "K0121112B")
