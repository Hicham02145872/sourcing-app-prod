# Rapport de Robustesse Technique : Système de Tracking
**Sourcing App - Audit & Préconisations**

---

## 📋 Résumé Exécutif
Ce rapport évalue la fiabilité du système de tracking hybride (API 17Track + Scraping Selenium). Bien que fonctionnel et ingénieux, le système présente des points critiques de fragilité qui pourraient entraîner des pannes de service ou une saturation des ressources serveur en conditions de charge réelle.

---

## 1. Audit Technique : Points de Fragilité

### 🏎️ Gestion du Cycle de Vie Navigateur (Auto-Cleaning)
**Risque** : Des instances de Chrome ("zombie processes") restent ouvertes si un script Python plante ou expire.
- **Impact** : Saturation graduelle de la RAM du VPS, ralentissement général du système.
- **Observation** : Le script utilise `driver.quit()` en bloc `finally`, mais n'a pas de sécurité de niveau "système" pour forcer la fermeture en cas de crash brutal de l'interpréteur Python.

### 📍 Chemins de Debug (Hardcoded Paths)
**Risque** : Présence de chemins Windows absolus (ex: `D:\telechargements\...`) dans les fichiers scrapers.
- **Impact** : Échec immédiat du déploiement sur un nouveau serveur ou en environnement de production standardisé.

### 🕵️ Détection Anti-Bot & IP Blocking
**Risque** : Utilisation d'une IP unique (celle du VPS) pour des requêtes répétées vers les fournisseurs chinois.
- **Impact** : Bannissement permanent de l'IP du serveur, rendant le tracking obsolète.

---

## 2. Recommandations de Robustesse

### 🛠️ Solution Immédiate : Normalisation de l'Environnement
- **Action** : Déplacer tous les chemins (Chrome Binary, ChromeDriver) dans le fichier `.env` via Laravel et les passer dynamiquement aux scripts Python.
- **Bénéfice** : Portabilité totale du code.

### 🛡️ Solution Systémique : Nettoyage des Processus
- **Action** : Implémenter un script de nettoyage automatique (Cleanup Job) tournant toutes les heures sur le VPS.
- **Commande Proposée (Windows)** : `taskkill /F /IM chrome.exe /T /FI "MEMUSAGE gt 150000"` (ferme les processus Chrome dépassant une certaine RAM).

### 🚀 Solution Avancée : Proxy Management & User-Agents
- **Action** : Intégrer une librairie de rotation de User-Agent et un provider de proxies (ex: Bright Data, Smartproxy).
- **Modification Code** :
```python
chrome_options.add_argument(f'--proxy-server={proxy_url}')
```
- **Bénéfice** : Le serveur devient invisible pour les protections anti-bot.

---

## 3. Stratégie de Maintenance (SLA)

Pour garantir un trait de réussite (Success Rate) supérieur à 98%, la maintenance doit suivre ces trois piliers :

1.  **Observabilité** : Créer un Dashboard admin affichant le ratio Succès/Échec par fournisseur (Itdida, ChoiceXP, etc.).
2.  **Gestion des Erreurs de Traduction** : Remplacer l'appel API Google systématique par un cache local (Redis/Database) pour les statuts fréquents.
3.  **Tests de Régression Hebdomadaires** : Lancer un script de test sur une liste de 10 numéros de référence chaque lundi pour vérifier que les sélecteurs CSS n'ont pas changé.

---
*Rapport établi pour l'équipe de développement et le management.*
