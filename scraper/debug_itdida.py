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
    
    chrome_binary = os.environ.get('CHROME_BINARY_PATH')
    if chrome_binary:
        options.binary_location = chrome_binary

    driver = webdriver.Chrome(options=options)
    
    try:
        url = f"https://ydl.itdida.com/query.xhtml?danHao={tracking_number}"
        print(f"--- Analyse de l'URL : {url} ---")
        driver.get(url)
        time.sleep(5) # On laisse le temps au JS de s'exécuter

        # 1. Analyse des tableaux présents
        tables = driver.find_elements(By.TAG_NAME, "table")
        print(f"Nombre de tableaux trouvés : {len(tables)}")
        
        for i, table in enumerate(tables):
            print(f"\nTableau #{i}:")
            print(f"ID: {table.get_attribute('id')}")
            print(f"Classes: {table.get_attribute('class')}")
            
            # On regarde les 2 premières lignes
            rows = table.find_elements(By.TAG_NAME, "tr")
            print(f"Nombre de lignes : {len(rows)}")
            if rows:
                for j, row in enumerate(rows[:3]):
                    print(f"  Ligne {j} HTML: {row.get_attribute('innerHTML')[:200]}...")

        # 2. Recherche spécifique de classes PrimeFaces
        pf_tables = driver.find_elements(By.CLASS_NAME, "ui-datatable")
        print(f"\nTableaux PrimeFaces trouvés : {len(pf_tables)}")

        # 3. Capture du texte complet pour voir si "No records" est là
        body_text = driver.find_element(By.TAG_NAME, "body").text
        if "No records found" in body_text:
            print("\nSTRICT: 'No records found' détecté dans le texte.")
        else:
            print("\nSTRICT: Données potentiellement présentes.")

    except Exception as e:
        print(f"Erreur durant l'analyse : {str(e)}")
    finally:
        driver.quit()

if __name__ == "__main__":
    tn = sys.argv[1] if len(sys.argv) > 1 else "K0121112B"
    analyze_ydl(tn)
