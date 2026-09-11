## Why

Le fonctionnement multi-flux n'a aucun SLA automatique : un dossier peut rester bloqué indéfiniment en `in_review`, `quoted` ou `negotiating` sans alerte ni blocage. Le owner impose des délais par statut (24h/24h/24h/48h) avec restriction automatique en cas de dépassement.

## What Changes

- Ajout des colonnes `status_changed_at` (timestamp du dernier changement de statut) et `is_restricted_due_to_delay` (booléen, défaut false) sur `sourcing_requests`.
- Mise à jour automatique de `status_changed_at` à chaque transition de statut via l'observer existant `SourcingRequestObserver` (réutilise le mécanisme `status_timestamps`).
- Nouvelle commande `workflow:check-deadlines` (horaires, ex. hourly) : parcourt les demandes en statuts SLA (`in_review`, `quoted`, `negotiating`, `accepted`), calcule l'échéance selon la config `fsb.sla` (24/24/24/48 h), passe `is_restricted_due_to_delay = true` si dépassée et notifie l'admin assigné.
- Déblocage automatique : toute transition de statut ultérieure remet `is_restricted_due_to_delay = false` et rafraîchit `status_changed_at`.
- Durées configurables dans `config('fsb.sla')` ; commande enregistrée dans le routing des scheduled tasks (`bootstrap/app.php` `withSchedule()`), désactivable par flag.
- Aucune modification des transitions de statut existantes ni de la logique de tracking.

## Capabilities

### New Capabilities
- `sla-deadlines-autolock`: suivi de l'ancienneté par statut, échéances configurables, restriction automatique au dépassement, déblocage à la prochaine transition.

### Modified Capabilities
<!-- Aucune spec existante concernée -->

## Impact

- Migration additive : colonnes `status_changed_at`, `is_restricted_due_to_delay` (nullable/défaut) sur `sourcing_requests`.
- `app/Observers/SourcingRequestObserver.php` : hook `updating` → gestion `status_changed_at` / déblocage.
- Nouvelle commande `app/Console/Commands/CheckWorkflowDeadlines.php` + enregistrement `withSchedule()` dans `bootstrap/app.php`.
- Notifications existantes (réutilisation du pattern `DossierAssignmentRemoved`, `SourcingRequestAssigned`).
- Config `config/fsb.php` ; tests Feature (transition → délai → restriction → déblocage).