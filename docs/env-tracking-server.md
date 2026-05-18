# Variables `.env` à ajouter sur le VPS pour P1.1

Ajoutez ces lignes dans `/var/www/sourcing-app/.env` sur le VPS
**après** que le serveur Flask est démarré et que `/health` répond OK.

```env
# --- Selenium HTTP Server (P1.1) ------------------------------------------
# Mettre à false pendant la migration, true une fois le serveur validé
TRACKING_SELENIUM_SERVER_ENABLED=true
TRACKING_SELENIUM_SERVER_URL=http://127.0.0.1:5001
TRACKING_SELENIUM_SERVER_TIMEOUT=30
```

## Procédure de migration sécurisée

1. Laisser `TRACKING_SELENIUM_SERVER_ENABLED=false`
2. Démarrer le serveur Flask avec Supervisor
3. Tester : `curl http://127.0.0.1:5001/health`
4. Tester un tracking réel :
   ```bash
   curl -X POST http://127.0.0.1:5001/track/choicexp \
     -H "Content-Type: application/json" \
     -d '{"tracking_number":"CHIL26155631"}'
   ```
5. Si OK → passer `TRACKING_SELENIUM_SERVER_ENABLED=true`
6. Vider le cache Laravel : `php artisan config:clear && php artisan cache:clear`
7. Surveiller les logs : `tail -f storage/logs/laravel.log storage/logs/tracking-server.log`

## Rollback instantané

Si problème : `TRACKING_SELENIUM_SERVER_ENABLED=false` + `php artisan config:clear`
Laravel revient automatiquement au mode process (ancien comportement).
