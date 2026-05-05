# Mise en route sur le VPS – Auto-update des statuts

À faire **sur ton VPS** pour que l’auto-update des statuts fonctionne.

---

## Étape 1 : Connexion au VPS

```bash
ssh ton-utilisateur@ip-de-ton-vps
```

---

## Étape 2 : Aller dans le projet

```bash
cd /var/www/sourcing-app
```

*(Remplace par le vrai chemin de ton projet si différent, par ex. `/home/user/sourcing-app`.)*

---

## Étape 3 : Activer l’auto-update dans `.env`

Ouvre le fichier :

```bash
nano .env
```

Ajoute (ou modifie) ces lignes :

```env
# Auto-Update Order Statuses
AUTO_UPDATE_ORDER_STATUSES_ENABLED=true
AUTO_UPDATE_MAX_ORDERS_PER_RUN=100
AUTO_UPDATE_MIN_INTERVAL_MINUTES=60
```

Sauvegarde : `Ctrl+O`, `Entrée`, puis quitte : `Ctrl+X`.

---

## Étape 4 : Ajouter le cron job

Ouvre le crontab :

```bash
crontab -e
```

Si on te demande un éditeur, choisis **nano** (souvent l’option 1).

À la **fin du fichier**, ajoute cette ligne (en gardant le bon chemin du projet) :

```bash
* * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /dev/null 2>&1
```

Sauvegarde : `Ctrl+O`, `Entrée`, puis quitte : `Ctrl+X`.

---

## Étape 5 : Vérifier que ça marche

```bash
# Voir la ligne cron
crontab -l

# Test sans modifier les statuts
cd /var/www/sourcing-app
php artisan orders:auto-update-statuses --dry-run

# Lancer une vraie exécution (optionnel)
php artisan orders:auto-update-statuses
```

Après une exécution, les logs sont ici :

```bash
tail -f storage/logs/auto-update-statuses.log
```

(`Ctrl+C` pour arrêter le suivi des logs.)

---

## Récap

| # | Action |
|---|--------|
| 1 | Se connecter en SSH au VPS |
| 2 | Aller dans le dossier du projet (`cd ...`) |
| 3 | Mettre `AUTO_UPDATE_ORDER_STATUSES_ENABLED=true` dans `.env` |
| 4 | Ajouter la ligne cron avec `crontab -e` |
| 5 | Tester avec `--dry-run` et regarder les logs |

Une fois fait, le cron appelle Laravel toutes les minutes ; Laravel lance `orders:auto-update-statuses` **toutes les 4 heures** automatiquement.
