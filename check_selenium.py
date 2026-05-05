import os
import sys
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

def check_env():
    print(f"Python Version: {sys.version}")
    print(f"Current Directory: {os.getcwd()}")
    
    options = Options()
    options.add_argument("--headless=new")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    
    try:
        driver = webdriver.Chrome(options=options)
        print("Selenium/Chrome check: SUCCESS")
        driver.quit()
    except Exception as e:
        print(f"Selenium/Chrome check: FAILED - {str(e)}")

if __name__ == "__main__":
    check_env()
