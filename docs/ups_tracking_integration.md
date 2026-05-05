# Intégration du tracking UPS (Selenium)

## Vue d’ensemble

UPS a été ajouté comme transporteur basé sur Selenium, sur le même modèle qu’Itdida et ChoiceXP.

## Fichiers concernés

| Fichier | Rôle |
|--------|------|
| `scraper/ups_tracker.py` | Script Python Selenium : ouvre ups.com/track, extrait statut et jalons, sortie JSON sur stdout |
| `app/Services/Tracking/UPSTrackingService.php` | Service Laravel : lance le script, parse le JSON, normalise en `events` / `current_status` |
| `app/Services/Tracking/UnifiedTrackingService.php` | Détection du provider UPS, envoi en job Selenium, cache, logs |

## Détection du provider

- **Par numéro** : préfixe `1Z` (ex. `1Z14V4W16890506495`) → provider `ups`.
- **Par carrier** : si le carrier (nom de la société de livraison ou `tracking_carrier` de la commande) contient `"ups"` → provider `ups`.

## Comportement

- Les numéros UPS sont traités comme les autres providers Selenium : une **job** `RunSeleniumTrackingJob` est dispatchée, le premier appel retourne `status: pending` avec un message invitant à rafraîchir ; après exécution du job, le résultat est en cache (TTL configurable dans `config/tracking.php`).
- Format de sortie unifié : `success`, `tracking_number`, `current_status`, `current_status_fr`, `events` (tableau avec `date`, `status`, `location`).

## Tests

- **CLI** : `python scraper/ups_tracker.py "1Z14V4W16890506495"` (ou `--visible` pour ouvrir le navigateur).
- **Laravel** : `php artisan tracking:test-regression --provider=ups` (après avoir au moins une entrée `TrackingLog` avec provider UPS).

## Prérequis

- Python 3 avec `selenium` et `webdriver-manager` (ou `CHROMEDRIVER_PATH` défini).
- Chrome/Chromium et éventuellement `CHROME_BINARY_PATH` / `CHROMEDRIVER_PATH` dans `.env` (voir `config/tracking.php`).

## Transporteur côté admin

Pour que les commandes soient associées à UPS et que le carrier soit envoyé au tracking, créer une société de livraison (admin) dont le **nom** contient « UPS » (ou renseigner le champ approprié utilisé comme `tracking_carrier`).
