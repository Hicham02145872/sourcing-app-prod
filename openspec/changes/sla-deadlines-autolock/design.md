## Context

- `status_timestamps` (JSON, colonne `sourcing_requests.status_timestamps`) est déjà alimenté à chaque changement (`SourcingRequest::booted()` hook `updating`, l.32-38) : historique fiable des transitions.
- `SourcingRequestObserver::updating()` (l.20-31) auto-affecte l'admin lors du passage en `in_review` : point idéal pour brancher `status_changed_at` / déblocage.
- Aucun scheduler n'est enregistré (pas de `withSchedule()` dans `bootstrap/app.php`) : le module exige son activation ; le cron OS côté prod (`* * * * * php artisan schedule:run`) reste une étape ops à confirmer.
- Convention flags : `FeatureFlagService` (DB).

## Goals / Non-Goals

**Goals:**
- Suivre l'ancienneté d'une demande dans son statut courant (`status_changed_at`), avec restriction automatique (`is_restricted_due_to_delay`) dès dépassement des délais configurés (24/24/24/48 h pour `in_review`, `quoted`, `negotiating`, `accepted`).
- Déblocage automatique à la prochaine transition ; notification de l'admin assigné au dépassement.
- Command `workflow:check-deadlines` planifiée (hourly) ; délais configurables.

**Non-Goals:**
- Pas de modification des transitions, pas d'impact sur le tracking ni l'affichage des durées client.
- Pas de restriction d'ordre (l'ordre garde son flux actuel).

## Decisions

- **Colonnes** : migration additive `sourcing_requests.status_changed_at` (nullable timestamp) + `is_restricted_due_to_delay` (boolean default false). Backfill : `status_changed_at = coalesce(status_timestamps[status], updated_at)` pour les lignes existantes.
- **Hook** : dans `SourcingRequestObserver::updating()` — si `isDirty('status')` : poser `status_changed_at = now()` et `is_restricted_due_to_delay = false` (réinitialise le blocage à chaque mouvement). La date actuelle de référence reste `status_changed_at` (plus direct que le JSON pour les requêtes et index).
- **Commande** : `app/Console/Commands/CheckWorkflowDeadlines` — pour chaque statut configuré en SLA, `where(status, X)->where('status_changed_at', '<', now()->subHours(H))->where('is_restricted_due_to_delay', false)` → passe la restriction à true + notifie l'admin assigné via `Notifications\SlaDeadlineExceeded`. Ignore `pending` et les statuts non SLA.
- **Activation scheduler** : `bootstrap/app.php` prend un `->withSchedule(...)` horaire `workflow:check-deadlines` (et le crontab `schedule:run` en prod, étape ops à valider). Flag `sla_deadlines_autolock` (FeatureFlagService) : commande no-op si flag désactivé.
- **Délais** : `config/fsb.php` → `sla` = `in_review: 24, quoted: 24, negotiating: 24, accepted: 48` (heures).
- **Tests** : Feature — transition met `status_changed_at` ; `workflow:check-deadlines` avec demande en `in_review` vieille de 25 h → restreint + notifie ; transition après restriction → flag remis à false + timestamp actualisé ; statut non SLA non restreint ; flag off → commande sans effet.

## Risks / Trade-offs

- Activer `withSchedule()` expose le `schedule:list` globalement → aucun autre job n'existe ; impact nul, mais vérifier `schedule:run` côté prod.
- Dérive d'horloge entre containers → `now()` côté worker ; acceptable.
- On ne rétro-écrit pas les retards passés (backfill timestamp seulement) → les retards ne s'activent qu'à partir du déploiement ; rollback = suppression de colonnes + retrait du withSchedule.