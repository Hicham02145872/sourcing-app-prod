# Rapport d'Intégration : Scraping Logistique (YDL & Choice Express)

Ce rapport détaille la méthode d'extraction des statuts pour **YDL Tracking** et **Choice Express**, identifiés comme les deux intégrations les plus directes à implémenter avec Crawlee.

---

## 🚀 1. YDL Tracking (`ydl.itdida.com`)

L'intégration de YDL est très stable car elle utilise une structure de tableau standard.

### Informations Clés
- **URL de base** : `https://ydl.itdida.com/query.xhtml?danHao=[TRACKING_NUMBER]`
- **Vérification Réelle** : `K0121112B` -> `已签收` (Livré) le `2025-11-22`.

### Sélecteurs DOM (Extraction)
| Donnée | Sélecteur CSS | Description |
| :--- | :--- | :--- |
| **Statut Récent** | `.ui-datatable-data tr:first-child td:nth-child(4)` | 4ème colonne du premier rang |
| **Date/Heure** | `.ui-datatable-data tr:first-child td:nth-child(3)` | 3ème colonne du premier rang |
| **Tableau entier** | `.ui-datatable-data` | Conteneur de l'historique complet |

### Logique d'Implémentation
```javascript
// Dans Crawlee (PlaywrightCrawler)
await page.goto(`https://ydl.itdida.com/query.xhtml?danHao=${trackingNumber}`);
// Parfois, le paramètre URL nécessite un clic sur le bouton de recherche pour rafraîchir
await page.click('#searchBtn'); 
await page.waitForSelector('.ui-datatable-data');

const status = await page.locator('.ui-datatable-data tr:first-child td:nth-child(4)').innerText();
const date = await page.locator('.ui-datatable-data tr:first-child td:nth-child(3)').innerText();
```

---

## 🚀 2. Choice Express (`air.choicexp.com`)

Ce site utilise un affichage moderne sous forme de liste (MUI). Bien que dynamique (AJAX), les sélecteurs sont très précis.

### Informations Clés
- **URL de base** : `https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp`
- **Vérification Réelle** : `DBC25421314` -> `Shipment had picked up and sign-off` le `2025-12-13`.

### Sélecteurs DOM (Extraction)
| Donnée | Sélecteur CSS | Description |
| :--- | :--- | :--- |
| **Statut Récent** | `#item1 .mui-table-view-cell:first-child .mui-media-body` | Titre du premier item de liste |
| **Date** | `#item1 .mui-table-view-cell:first-child .mui-pull-left` | Texte à gauche (date) |
| **Champ Saisie** | `#hbl_search` | Input pour le numéro de tracking |
| **Bouton Recherche**| `#searchBtn` | Bouton pour lancer l'AJAX |

### Logique d'Implémentation
```javascript
// Dans Crawlee (PlaywrightCrawler)
await page.goto('https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp');
await page.fill('#hbl_search', trackingNumber);
await page.click('#searchBtn');

// Attendre que la liste de résultats apparaisse
await page.waitForSelector('#item1 .mui-table-view-cell');

const status = await page.locator('#item1 .mui-media-body').first().innerText();
const date = await page.locator('#item1 .mui-pull-left').first().innerText();
```

---

## 📊 3. Synthèse de l'Extraction

Les deux sites peuvent être gérés via un seul script Crawlee en utilisant des conditions basées sur l'URL.

| Site | Méthode | Difficulté | Fiabilité |
| :--- | :--- | :--- | :--- |
| **YDL** | URL + Table | ⭐ (Facile) | ✅ Très Haute |
| **Choice Express**| Form + List | ⭐⭐ (Simple) | ✅ Haute |

> [!IMPORTANT]
> **Observation sur J&T Express** : Comme confirmé avec le numéro `JTE300387065227`, le captcha Tencent bloque l'accès immédiat. Une intégration spécifique avec un résolveur de captcha est indispensable pour J&T.

---

## 📸 Preuves Visuelles
![YDL Result](C:/Users/PC/.gemini/antigravity/brain/13e0fd24-1c9b-4836-b60f-1e13c8bc9745/ydl_tracking_result_1766352384792.png)
*Preuve d'extraction réussie pour YDL (K0121112B)*

![Choice Result](C:/Users/PC/.gemini/antigravity/brain/13e0fd24-1c9b-4836-b60f-1e13c8bc9745/choice_express_result_1766352549838.png)
*Preuve d'extraction réussie pour Choice Express (DBC25421314)*
