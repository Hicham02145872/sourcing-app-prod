# Guide Complet : Configuration du Cron Job pour l'Auto-Update des Statuts
## Walkthrough étape par étape pour VPS

---

## 📋 Table des Matières

1. [Prérequis](#prérequis)
2. [Configuration Laravel Scheduler](#configuration-laravel-scheduler)
3. [Configuration du Cron Job sur VPS](#configuration-du-cron-job-sur-vps)
4. [Test et Validation](#test-et-validation)
5. [Monitoring et Logs](#monitoring-et-logs)
6. [Dépannage](#dépannage)
7. [Bonnes Pratiques](#bonnes-pratiques)

---

## ✅ Prérequis

Avant de commencer, assurez-vous d'avoir :

- ✅ Laravel installé et fonctionnel sur le VPS
- ✅ Accès SSH au VPS
- ✅ Permissions d'écriture dans les répertoires de logs
- ✅ La commande `AutoUpdateOrderStatusesFromTracking` créée et testée
- ✅ Variables d'environnement configurées (`.env`)

---

## 🔧 Étape 1 : Configuration Laravel Scheduler

### 1.1 Vérifier le fichier `app/Console/Kernel.php`

Ouvrez `app/Console/Kernel.php` et vérifiez que la méthode `schedule()` existe :

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto-update des statuts de commande basés sur le tracking
        $schedule->command('orders:auto-update-statuses')
            ->everyFourHours() // Toutes les 4 heures
            ->withoutOverlapping() // Éviter les exécutions simultanées
            ->onOneServer() // Si plusieurs serveurs, exécuter sur un seul
            ->appendOutputTo(storage_path('logs/auto-update-statuses.log'))
            ->emailOutputOnFailure(config('mail.admin_email')); // Optionnel : email en cas d'échec
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
```

### 1.2 Options de Planification Disponibles

Vous pouvez ajuster la fréquence selon vos besoins :

```php
// Toutes les heures
->hourly()

// Toutes les 2 heures
->everyTwoHours()

// Toutes les 4 heures (recommandé)
->everyFourHours()

// Toutes les 6 heures
->everySixHours()

// Toutes les 12 heures
->everyTwelveHours()

// Quotidien à une heure spécifique
->dailyAt('02:00') // À 2h du matin

// Plusieurs fois par jour
->twiceDaily(1, 13) // À 1h et 13h

// Personnalisé avec cron expression
->cron('0 */4 * * *') // Toutes les 4 heures
```

### 1.3 Variables d'Environnement (.env)

Ajoutez ces variables dans votre fichier `.env` :

```env
# Auto-Update Order Statuses
AUTO_UPDATE_ORDER_STATUSES_ENABLED=true
AUTO_UPDATE_CHECK_INTERVAL_HOURS=4
AUTO_UPDATE_MAX_ORDERS_PER_RUN=100
AUTO_UPDATE_MIN_INTERVAL_MINUTES=60

# Email pour notifications d'échec (optionnel)
MAIL_ADMIN_EMAIL=admin@example.com
```

---

## 🖥️ Étape 2 : Configuration du Cron Job sur VPS

### 2.1 Se Connecter au VPS

```bash
ssh user@votre-vps-ip
# ou
ssh user@votre-domaine.com
```

### 2.2 Accéder à l'Éditeur Cron

```bash
crontab -e
```

Si c'est la première fois, choisissez un éditeur (nano est recommandé pour débutants) :

```bash
# Choisir l'éditeur
Select an editor.  To change later, run 'select-editor'.
  1. /bin/nano        <---- easiest
  2. /usr/bin/vim.tiny
  3. /bin/ed

Choose 1-3 [1]: 1
```

### 2.3 Ajouter la Ligne Cron pour Laravel Scheduler

Ajoutez cette ligne à la fin du fichier crontab :

```bash
# Laravel Scheduler - Auto-Update Order Statuses
* * * * * cd /chemin/vers/votre/projet && php artisan schedule:run >> /dev/null 2>&1
```

**⚠️ IMPORTANT** : Remplacez `/chemin/vers/votre/projet` par le chemin absolu vers votre projet Laravel.

**Exemple** :
```bash
* * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /dev/null 2>&1
```

### 2.4 Explication de la Ligne Cron

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
│ │ │ │ │ │                                    │ │ │ │ │
│ │ │ │ │ └─ Commande à exécuter              │ │ │ │ └─ Rediriger stderr vers stdout puis vers /dev/null
│ │ │ │ │                                    │ │ │ └─ Rediriger stdout vers /dev/null (pas de sortie)
│ │ │ │ │                                    │ │ └─ Rediriger stdout
│ │ │ │ │                                    │ └─ Exécuter la commande schedule:run
│ │ │ │ │                                    └─ Laravel Artisan
│ │ │ │ │
│ │ │ │ └─ Jour de la semaine (0-7, 0 et 7 = dimanche)
│ │ │ └─ Mois (1-12)
│ │ └─ Jour du mois (1-31)
│ └─ Heure (0-23)
└─ Minute (0-59)

* * * * * signifie "chaque minute"
```

**Pourquoi chaque minute ?**
- Laravel Scheduler vérifie toutes les minutes quelles tâches doivent être exécutées
- La fréquence réelle est définie dans `Kernel.php` (ex: `->everyFourHours()`)
- C'est la méthode recommandée par Laravel

### 2.5 Sauvegarder et Quitter

**Avec nano** :
- Appuyez sur `Ctrl + X`
- Tapez `Y` pour confirmer
- Appuyez sur `Enter` pour sauvegarder

**Avec vim** :
- Appuyez sur `Esc`
- Tapez `:wq` puis `Enter`

### 2.6 Vérifier que le Cron Job est Installé

```bash
crontab -l
```

Vous devriez voir votre ligne :
```
* * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /dev/null 2>&1
```

### 2.7 Vérifier les Permissions

Assurez-vous que PHP peut exécuter les commandes Artisan :

```bash
# Vérifier que PHP est accessible
which php
# Devrait afficher : /usr/bin/php ou similaire

# Tester manuellement la commande
cd /var/www/sourcing-app
php artisan schedule:run

# Vérifier les permissions du fichier artisan
ls -la artisan
# Devrait afficher : -rwxr-xr-x ou similaire
```

---

## 🧪 Étape 3 : Test et Validation

### 3.1 Tester la Commande Manuellement

```bash
# Se connecter au VPS
ssh user@votre-vps-ip

# Aller dans le répertoire du projet
cd /var/www/sourcing-app

# Exécuter la commande manuellement
php artisan orders:auto-update-statuses

# Ou tester le scheduler
php artisan schedule:run
```

### 3.2 Vérifier les Logs

```bash
# Voir les logs de la commande
tail -f storage/logs/auto-update-statuses.log

# Voir les logs Laravel généraux
tail -f storage/logs/laravel.log

# Voir les logs en temps réel
tail -f storage/logs/*.log
```

### 3.3 Tester le Cron Job

Attendez quelques minutes, puis vérifiez :

```bash
# Vérifier les logs du cron système
grep CRON /var/log/syslog | tail -20

# Ou sur certaines distributions
grep CRON /var/log/cron.log | tail -20

# Vérifier que Laravel Scheduler s'exécute
php artisan schedule:list
```

### 3.4 Forcer une Exécution Immédiate

Pour tester sans attendre :

```bash
# Exécuter la commande directement
php artisan orders:auto-update-statuses

# Ou forcer le scheduler à exécuter toutes les tâches
php artisan schedule:run --verbose
```

---

## 📊 Étape 4 : Monitoring et Logs

### 4.1 Configuration des Logs

Le fichier `Kernel.php` configure déjà les logs :

```php
->appendOutputTo(storage_path('logs/auto-update-statuses.log'))
```

### 4.2 Vérifier les Logs Régulièrement

```bash
# Voir les dernières lignes
tail -n 50 storage/logs/auto-update-statuses.log

# Chercher des erreurs
grep -i error storage/logs/auto-update-statuses.log

# Compter les exécutions réussies
grep -i "success\|completed" storage/logs/auto-update-statuses.log | wc -l
```

### 4.3 Script de Monitoring (Optionnel)

Créez un script `monitor-auto-update.sh` :

```bash
#!/bin/bash

LOG_FILE="/var/www/sourcing-app/storage/logs/auto-update-statuses.log"
ALERT_EMAIL="admin@example.com"

# Vérifier si le log existe et a été mis à jour dans les dernières 5 heures
if [ -f "$LOG_FILE" ]; then
    LAST_RUN=$(stat -c %Y "$LOG_FILE")
    CURRENT_TIME=$(date +%s)
    DIFF=$((CURRENT_TIME - LAST_RUN))
    
    # 5 heures = 18000 secondes
    if [ $DIFF -gt 18000 ]; then
        echo "⚠️ Le cron job n'a pas été exécuté depuis plus de 5 heures !" | mail -s "Alerte: Cron Job Auto-Update" "$ALERT_EMAIL"
    fi
else
    echo "⚠️ Le fichier de log n'existe pas !" | mail -s "Alerte: Cron Job Auto-Update" "$ALERT_EMAIL"
fi
```

Rendez-le exécutable :

```bash
chmod +x monitor-auto-update.sh
```

Ajoutez-le au cron pour vérifier toutes les heures :

```bash
crontab -e
# Ajouter :
0 * * * * /chemin/vers/monitor-auto-update.sh
```

### 4.4 Monitoring avec Laravel Telescope (Optionnel)

Si vous utilisez Laravel Telescope :

```bash
# Installer Telescope
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Accéder à l'interface
# http://votre-domaine.com/telescope
```

---

## 🔍 Étape 5 : Dépannage

### 5.1 Le Cron Job ne s'Exécute Pas

**Vérifier que le cron est actif** :
```bash
# Vérifier le service cron
sudo systemctl status cron
# ou
sudo service cron status

# Redémarrer si nécessaire
sudo systemctl restart cron
```

**Vérifier les permissions** :
```bash
# Vérifier que l'utilisateur peut exécuter PHP
php -v

# Vérifier les permissions du répertoire
ls -la /var/www/sourcing-app
```

**Vérifier les logs système** :
```bash
# Voir les erreurs du cron
grep CRON /var/log/syslog | grep -i error
```

### 5.2 La Commande ne Trouve Pas PHP

**Solution 1 : Utiliser le chemin complet** :
```bash
# Trouver le chemin de PHP
which php
# Exemple : /usr/bin/php

# Modifier le crontab avec le chemin complet
* * * * * cd /var/www/sourcing-app && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

**Solution 2 : Définir PATH dans le crontab** :
```bash
crontab -e
# Ajouter en haut du fichier :
PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
* * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /dev/null 2>&1
```

### 5.3 Erreurs de Permissions

```bash
# Vérifier les permissions du répertoire storage
ls -la storage/logs/

# Donner les permissions nécessaires
chmod -R 775 storage/logs/
chown -R www-data:www-data storage/logs/
```

### 5.4 La Commande S'Exécute mais Ne Fait Rien

**Vérifier les variables d'environnement** :
```bash
# Vérifier que AUTO_UPDATE_ORDER_STATUSES_ENABLED=true
grep AUTO_UPDATE .env

# Vérifier les logs pour voir pourquoi rien n'est traité
tail -f storage/logs/auto-update-statuses.log
```

**Vérifier qu'il y a des commandes à traiter** :
```bash
# Se connecter à la base de données
php artisan tinker

# Vérifier les commandes éligibles
App\Models\SourcingOrder::whereNotNull('tracking_number')
    ->whereNotNull('real_tracking_assigned_at')
    ->whereIn('status', ['shipment_preparing', 'in_transit_china', 'arrival_uae'])
    ->count();
```

### 5.5 Debug Mode

Pour voir plus de détails, modifiez temporairement le crontab :

```bash
crontab -e
# Remplacer par :
* * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /var/www/sourcing-app/storage/logs/cron-debug.log 2>&1
```

Puis surveillez le fichier :
```bash
tail -f storage/logs/cron-debug.log
```

---

## ✅ Étape 6 : Bonnes Pratiques

### 6.1 Sécurité

- ✅ Ne jamais exposer les fichiers `.env` publiquement
- ✅ Utiliser des permissions restrictives sur les fichiers sensibles
- ✅ Ne pas logger les informations sensibles (mots de passe, tokens)
- ✅ Utiliser HTTPS pour toutes les communications

### 6.2 Performance

- ✅ Limiter le nombre de commandes traitées par exécution (`AUTO_UPDATE_MAX_ORDERS_PER_RUN`)
- ✅ Utiliser `withoutOverlapping()` pour éviter les exécutions simultanées
- ✅ Utiliser `onOneServer()` si vous avez plusieurs serveurs
- ✅ Monitorer les temps d'exécution dans les logs

### 6.3 Maintenance

- ✅ Vérifier les logs régulièrement (quotidiennement au début)
- ✅ Nettoyer les anciens logs périodiquement
- ✅ Surveiller l'espace disque
- ✅ Tester après chaque déploiement

### 6.4 Backup

Avant de modifier quoi que ce soit :

```bash
# Backup du crontab
crontab -l > crontab-backup-$(date +%Y%m%d).txt

# Backup de la configuration Laravel
cp app/Console/Kernel.php app/Console/Kernel.php.backup
```

---

## 📝 Checklist de Configuration

Utilisez cette checklist pour vous assurer que tout est configuré :

- [ ] Laravel Scheduler configuré dans `app/Console/Kernel.php`
- [ ] Commande `orders:auto-update-statuses` créée et testée
- [ ] Variables d'environnement configurées dans `.env`
- [ ] Cron job ajouté avec `crontab -e`
- [ ] Chemin absolu correct dans le crontab
- [ ] Permissions correctes sur les répertoires
- [ ] Test manuel de la commande réussi
- [ ] Test du scheduler réussi (`php artisan schedule:run`)
- [ ] Logs configurés et accessibles
- [ ] Monitoring en place (optionnel mais recommandé)
- [ ] Backup du crontab effectué

---

## 🎯 Résumé des Commandes Essentielles

```bash
# 1. Éditer le crontab
crontab -e

# 2. Voir le crontab actuel
crontab -l

# 3. Tester la commande manuellement
cd /var/www/sourcing-app && php artisan orders:auto-update-statuses

# 4. Tester le scheduler
cd /var/www/sourcing-app && php artisan schedule:run

# 5. Voir les logs
tail -f storage/logs/auto-update-statuses.log

# 6. Vérifier les tâches planifiées
php artisan schedule:list

# 7. Vérifier le service cron
sudo systemctl status cron
```

---

## 📞 Support

Si vous rencontrez des problèmes :

1. Vérifiez les logs : `storage/logs/auto-update-statuses.log`
2. Vérifiez les logs système : `/var/log/syslog` ou `/var/log/cron.log`
3. Testez manuellement la commande
4. Vérifiez les permissions et les chemins
5. Consultez la documentation Laravel : https://laravel.com/docs/scheduling

---

**✅ Configuration terminée !** Votre système d'auto-update des statuts devrait maintenant fonctionner automatiquement selon la planification configurée.
