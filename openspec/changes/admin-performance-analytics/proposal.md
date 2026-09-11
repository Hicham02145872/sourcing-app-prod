## Why

Le super admin ne peut pas évaluer la charge/l'efficacité des admins : pas d'historique fiable des changements de statut, pas de stats par admin ni de filtres par date sur les vues.

## What Changes

- Nouvelle table `request_status_logs` (id, `sourcing_request_id`, `from_status`, `to_status`, `changed_by_user_id`, `changed_at`, index) alimentée par un observer `SourcingRequestObserver` à chaque transition de statut (réutilise l'événement `SourcingRequestStatusChanged` existant).
- Nouvel endpoint Super Admin `GET /superadmin/analytics/admin-performance` (w/ filtres `start_date` / `end_date`) : par admin, nombre de demandes `in_review`, nombre de réponses (`quoted`), taux d'acceptation, délai moyen réponse (pending→quoted).
- Composant de filtre par date (du/au) réutilisable, appliqué aux listes de demandes (admins) et utilisé par l'analytics.
- Rendu dans l'interface existante du super admin (blade) ; aucune modification des transitions de statut.
- Backfill : les transitions passées restent consultables via `sourcing_requests.status_timestamps` (JSON), pas de rétro-écriture dans la nouvelle table (traçage à partir du déploiement).

## Capabilities

### New Capabilities
- `request-status-history-log`: persistance de chaque changement de statut avec acteur et horodatage.
- `admin-performance-analytics`: statistiques par admin sur période (volume, réponses, acceptations, délais).
- `date-range-filter`: filtre du/au réutilisable sur les listes admin.

### Modified Capabilities
<!-- Aucune spec existante concernée -->

## Impact

- Migration : table `request_status_logs` + index ; `SourcingRequestObserver` (write log) ; éventuel rejeu `dispatch` pour les données vides.
- Nouveau contrôleur/route API super admin + vue blade analytics.
- Composant Livewire ou partial blade de filtre date réutilisable (statut existant : aucun composant de filtre date — à créer ; se branche au-dessus des listes actuelles).
- `route:list` avant/après, tests Feature (log écrasé, agrégats sur période, filtres).