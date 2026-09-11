## Context

- `SourcingRequestObserver` + hook `status_timestamps` couvrent déjà l'historique de statut par demande ; il n'existe **aucune table d'historique** exploitable pour des agrégats par admin ni de filtre par date.
- `AdminPerformanceTable` (Livewire) affiche déjà des compteurs par admin (nb demandes/ordres assignés, profit net) mais sans fenêtre temporelle ni statistiques par statut/délai.
- Convention groupe super admin : `routes/web.php:172` `Route::middleware('role:super_admin')`. Convention flags : `FeatureFlagService`.

## Goals / Non-Goals

**Goals:**
- Persister chaque changement de statut avec acteur et horodatage (`request_status_logs`).
- Page analytics super admin : par admin, sur période du/au — demandes prises en revue, réponses (quoted), acceptations, taux d'acceptation, délai moyen pending→quoted.
- Filtre date (du/au) réutilisable.

**Non-Goals:**
- Pas de modification des transitions de statut ni de l'ordre.
- Pas de backfill des transitions passées (rétro-traçage interdit par la règle « additive ») : l'historique démarre au déploiement ; données antérieures consultables via `status_timestamps`.

## Decisions

- **Table** `request_status_logs` : `id`, `sourcing_request_id` (FK cascade), `from_status` (nullable), `to_status`, `changed_by_user_id` (nullable, FK users nullOnDelete), `changed_at` (datetime, index), timestamps. **Écriture** : dans `SourcingRequestObserver::updating()` (isDirty('status')) — un seul point de capture, identique au mécanisme `status_timestamps` (plus fiable que l'événement déjà dispatché dans `transitionTo()`, qui manque les saves directes).
- **Analytics** : nouveau composant Livewire `AdminPerformanceAnalytics` (page `super-admin/analytics/admin-performance`), agrégats depuis `request_status_logs` filtrés sur `changed_at` en [start,end], regroupés par `changed_by_user_id` :
  - « pris en revue » : to_status = `in_review` ;
  - « répondu » : to_status = `quoted` ;
  - « acceptés » : to_status = `accepted` ;
  - taux = acceptés / réponses ;
  - délai moyen = AVG entre le `changed_at` de la transition → `in_review` et celui → `quoted` par demande (temps de traitement). On conserve la table `AdminPerformanceTable` existante inchangée (zéro régression), la page analytics la remplace dans la navigation super admin.
- **Filtre date réutilisable** : propriétés `startDate`/`endDate` (Livewire, format `Y-m-d`) + partial blade `livewire/partials/date-range-filter.blade.php`, hooks `applyDateRange`/`resetDateRange`. Appliqué à la page analytics ; extensible aux listes admin ensuite.
- **Flag** : clé `admin_performance_analytics` (FeatureFlagService) ; page 404 si désactivé (pattern middleware `feature`).
- **Tests** : Feature — log écrit à chaque transition avec acteur ; agrégats sur période correcte (hors période exclu) ; filtre du/au ; délai moyen calculé ; flag off → 404. `route:list` avant/après.

## Risks / Trade-offs

- Historique commence au déploiement → les métriques pré-déploiement sont vides ; mitigation : mention textuelle en UI (« données collectées à partir de … ») et fallback possible via `status_timestamps` ultérieur.
- Volume de lignes sur dossiers actifs → index sur `changed_at` ; purge non prévue.
- Rollback : drop table + retrait route ; aucune dépendance.