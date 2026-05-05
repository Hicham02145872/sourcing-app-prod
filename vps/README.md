# 🚀 Guide de Déploiement : Système de Suivi (Ubuntu VPS)

Ce guide détaille chaque étape pour installer et configurer le système de suivi automatisé (Selenium + Python) sur votre serveur de production.

---

## 📋 Prérequis Système
- Serveur sous **Ubuntu 20.04 LTS** ou plus récent.
- Accès **root** ou utilisateur avec privilèges `sudo`.

---

## 🛠 Étape 1 : Mise à jour du système
Avant toute installation, assurez-vous que votre serveur est à jour.
```bash
sudo apt update && sudo apt upgrade -y
```

---

## 🐍 Étape 2 : Installation de Python 3
Le système utilise Python pour piloter Chrome et extraire les données.
```bash
# Installation de Python et de l'outil venv
sudo apt install python3 python3-pip python3-venv -y

# Vérification
python3 --version
```

---

## 🌐 Étape 3 : Installation de Google Chrome
Selenium a besoin d'un navigateur réel pour contourner les protections anti-robot.
```bash
# Téléchargement du paquet officiel
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb

# Installation du paquet
sudo apt install ./google-chrome-stable_current_amd64.deb -y

# Vérification (doit afficher Google Chrome 120+)
google-chrome --version
```

---

## 📦 Étape 4 : Installation des dépendances Python
Installez les outils nécessaires dans un environnement virtuel dédié pour éviter les conflits systèmes.
```bash
# Créer le dossier pour l'environnement virtuel et installer
python3 -m venv /var/www/sourcing-app/venv
/var/www/sourcing-app/venv/bin/pip install selenium webdriver-manager
```

---

## ⚙️ Étape 5 : Configuration Laravel (.env)
Modifiez votre fichier `.env` sur le serveur pour activer le système.
```bash
nano .env
```
Ajoutez/Modifiez les lignes suivantes :
```env
# Chemin vers l'exécutable Python du venv (IMPORTANT)
TRACKING_PYTHON_PATH="/var/www/sourcing-app/venv/bin/python"

# Indique à Selenium où trouver Chrome sur Ubuntu
CHROME_BINARY_PATH="/usr/bin/google-chrome"

# Chemin du Driver (Laisser vide pour gestion automatique par webdriver-manager)
CHROMEDRIVER_PATH=""

# Clés API pour le fallback
SEVENTEEN_TRACK_API_KEY="votre_cle_ici"
SEVENTEEN_TRACK_BASE_URL="https://api.17track.net/track/v2"

# Cache (Recommandé : 30 minutes)
TRACKING_CACHE_TTL=30
```

---

## 📂 Étape 6 : Droits d'accès
Le serveur web doit pouvoir écrire des captures d'écran en cas d'erreur pour le débogage.
```bash
sudo chown -R www-data:www-data storage/app/public
sudo chmod -R 775 storage/app/public
```

---

## 🧪 Étape 7 : Test de fonctionnement
Lancez un test manuel via la console Laravel pour confirmer que tout est OK :
```bash
php artisan tinker
```
Puis tapez cette commande dans l'invite :
```php
app(App\Services\Tracking\UnifiedTrackingService::class)->track('k0121112B');
```

---

## 🐇 Étape 8 : Lancer le Worker de Queue
Le système de suivi Selenium utilise les files d'attente pour ne pas bloquer l'application et limiter l'usage RAM.
Vous devez lancer un worker pour traiter les demandes :

```bash
# Traitement des jobs (en arrière-plan ou via Supervisor)
php artisan queue:work --timeout=600
```

*Note : Un mécanisme de verrouillage (lock) est intégré pour empêcher plusieurs instances Chrome de se lancer en même temps, même si vous avez plusieurs workers.*

---

## 🚑 Dépannage (Troubleshooting)

### Erreur "SessionNotCreated"
Cela arrive si la version de Chrome a été mise à jour mais pas le driver. Le script utilise `webdriver-manager` pour le corriger automatiquement, mais si l'erreur persiste :
```bash
/var/www/sourcing-app/venv/bin/pip install --upgrade selenium webdriver-manager
```

### Problème de RAM (VPS < 2GB)
Chrome est gourmand. Si le script plante ou si votre serveur devient lent, suivez ces recommandations :

1. **Ajouter du SWAP (Mémoire Virtuelle)** :
   Donnez de l'air à votre RAM en utilisant le disque dur.
   ```bash
   sudo fallocate -l 2G /swapfile
   sudo chmod 600 /swapfile
   sudo mkswap /swapfile
   sudo swapon /swapfile
   echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
   ```

2. **Utiliser les Queues Laravel (Files d'attente)** :
   Le système utilise désormais automatiquement des **Jobs** pour les suivis lourds (Selenium).
   Assurez-vous que votre worker de queue est bien lancé (voir Étape 8).

3. **Nettoyage automatique** :
   Ajoutez une tâche Cron pour tuer les processus Chrome orphelins chaque nuit.
   ```bash
   0 3 * * * pkill -o chromium && pkill -o chrome
   ```
