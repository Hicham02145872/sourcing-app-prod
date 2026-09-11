## 1. Migration & config

- [x] 1.1 Create migration adding `status_changed_at` (nullable timestamp) and `is_restricted_due_to_delay` (boolean, default false) to `sourcing_requests`, with backfill from `status_timestamps[status]` / `updated_at`
- [x] 1.2 Add `sla` section to `config/fsb.php` (`in_review: 24, quoted: 24, negotiating: 24, accepted: 48`)

## 2. Suivi par l'observer

- [x] 2.1 In `SourcingRequestObserver::updating()`, when `isDirty('status')` set `status_changed_at = now()` and `is_restricted_due_to_delay = false`

## 3. Commande de contrôle

- [x] 3.1 Create `app/Console/Commands/CheckWorkflowDeadlines` (signature `workflow:check-deadlines`): for each SLA status, flag overdue requests `is_restricted_due_to_delay = true` and notify the assigned admin
- [x] 3.2 Create `app/Notifications/SlaDeadlineExceeded` (reuse notification conventions; bilingual content)
- [x] 3.3 Gate the command with `FeatureFlagService::isEnabled('sla_deadlines_autolock', ...)` (no-op when disabled)
- [x] 3.4 Register hourly schedule via `->withSchedule(...)` in `bootstrap/app.php`; confirm `php artisan schedule:list` shows the task

## 4. Tests

- [x] 4.1 Feature test: transition updates `status_changed_at` and clears restriction
- [x] 4.2 Feature test: `workflow:check-deadlines` marks a 25 h in_review request restricted + notifies admin
- [x] 4.3 Feature test: non-SLA status (pending) not restricted
- [x] 4.4 Feature test: transition after restriction clears it and refreshes timestamp
- [x] 4.5 Feature test: flag `sla_deadlines_autolock` disabled → command is a no-op
- [x] 4.6 Verify backfill ran correctly on existing rows (test DB)

## 5. Bandeau persistant SLA (reuse du pattern Meta Ads)

- [x] 5.1 Dashboard admin : `$showSlaOverdueBanner` / `$slaOverdueCount` / `$slaOverdueByStatus` pour les admins non super admin
- [x] 5.2 Extension du partial `workflow-alert-banner` : bandeau rouge avec CTA par statut → `?status=<status>&overdue=1`
- [x] 5.3 Filtre `overdue=1` sur l'index `AdminSourcingRequestController` (liste + compteurs)
- [x] 5.4 Lang keys `sla.*` (fr/ar/en)
- [x] 5.5 Tests : bandeau visible/masqué, filtre `overdue=1` ne renvoie que les restreints