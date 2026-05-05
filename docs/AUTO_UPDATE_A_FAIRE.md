# Auto-Update des Statuts : Ce qu'il reste à faire

Le **code est déjà inclus** (TrackingStatusMapper, AutoUpdateOrderStatusFromTracking, commande Artisan, Kernel).  
Il ne reste qu’à **activer et configurer le cron job**.

**Règle de mapping** : Les événements de tracking ont souvent comme dernière destination **Dubaï**.  
« Delivered » avec lieu Dubaï = livré au **hub à Dubaï**, pas au client final. Le statut `delivered` (livraison au client) n’est appliqué que si la localisation n’est **pas** Dubaï (exclusions dans `config/tracking_status_mapping.php`).

---

## Checklist rapide

### 1. Activer dans `.env`

Sur le VPS (ou en local pour tester), ajoute dans ton `.env` :

```env
# Auto-Update Order Statuses
AUTO_UPDATE_ORDER_STATUSES_ENABLED=true
AUTO_UPDATE_MAX_ORDERS_PER_RUN=100
AUTO_UPDATE_MIN_INTERVAL_MINUTES=60
```

Optionnel (pour recevoir un email en cas d’échec du cron) :

```env
MAIL_ADMIN_EMAIL=ton-email@example.com
```

### 2. Ajouter le cron job sur le VPS

En SSH sur le VPS :

```bash
crontab -e
```

Ajoute **une seule ligne** (remplace `/var/www/sourcing-app` par le chemin réel de ton projet) :

```bash
* * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /dev/null 2>&1
```

Sauvegarde et quitte. Laravel Scheduler s’occupe du reste (la commande `orders:auto-update-statuses` tourne toutes les 4h).

### 3. Vérifier

```bash
# Voir le cron enregistré
crontab -l

# Tester la commande manuellement (sans modifier les statuts)
php artisan orders:auto-update-statuses --dry-run

# Lancer une vraie exécution
php artisan orders:auto-update-statuses

# Voir les logs
tail -f storage/logs/auto-update-statuses.log
```

---

## Récap

| Élément | Statut |
|--------|--------|
| Code (mapper, service, commande) | Déjà inclus |
| Scheduler dans `Kernel.php` | Déjà inclus |
| À faire | 1) `.env` avec `AUTO_UPDATE_ORDER_STATUSES_ENABLED=true` |
| À faire | 2) Une ligne dans `crontab` pour `schedule:run` |
| À faire | 3) Tester avec `--dry-run` puis surveiller les logs |

Guide détaillé (dépannage, monitoring, etc.) : `docs/CRON_JOB_SETUP_WALKTHROUGH.md`
