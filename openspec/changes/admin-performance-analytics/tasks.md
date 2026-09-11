## 1. Historique des statuts

- [x] 1.1 Create migration `request_status_logs` (id, sourcing_request_id FK cascade, from_status nullable, to_status, changed_by_user_id nullable FK users nullOnDelete, changed_at, timestamps; index on changed_at and sourcing_request_id)
- [x] 1.2 Create `App\Models\RequestStatusLog` (fillable + belongsTo relations)
- [x] 1.3 Write the log in `SourcingRequestObserver::updating()` when `isDirty('status')` (from = old status, to = new status, user = auth()->id(), changed_at = now())

## 2. Analytics super admin

- [x] 2.1 Create `App\Livewire\Admin\AdminPerformanceAnalytics` aggregating per admin from `request_status_logs` (reviews, responses, acceptances, acceptance rate, average in_review→quoted response time), honoring `startDate`/`endDate` range
- [x] 2.2 Add route `GET /super-admin/analytics/admin-performance` inside the `role:super_admin` group (name `super-admin.analytics.admin-performance`), page guarded by flag `admin_performance_analytics` (feature middleware)
- [x] 2.3 Add view `livewire/admin/admin-performance-analytics.blade.php` and nav entry; keep existing `AdminPerformanceTable` untouched
- [x] 2.4 Record/verify `route:list` before and after

## 3. Filtre date réutilisable

- [x] 3.1 Create partial `livewire/partials/date-range-filter.blade.php` with from/to inputs and apply/reset actions
- [x] 3.2 Wire the filter into `AdminPerformanceAnalytics` (properties `startDate`, `endDate`)

## 4. Tests

- [x] 4.1 Feature test: transition writes a `request_status_logs` row with actor and timestamps
- [x] 4.2 Feature test: transition without actor logs `changed_by_user_id = null`
- [x] 4.3 Feature test: analytics aggregates only within the selected period
- [x] 4.4 Feature test: acceptance rate null when no responses in period
- [x] 4.5 Feature test: date-range reset shows full dataset
- [x] 4.6 Feature test: flag `admin_performance_analytics` disabled → 404 on analytics page
- [x] 4.7 Run existing feature suite for regressions (transitions already covered by status_timestamps tests)