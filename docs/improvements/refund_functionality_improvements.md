# Refund Functionality Improvements

## Overview
The current refund workflow involves two main controllers:
- `App\Http\Controllers\Client\RefundRequestController` (client side)
- `App\Http\Controllers\Admin\RefundRequestController` (admin side)

Key steps:
1. **Client creates a refund request** – validated, stored in `refund_requests`, and the related `sourcing_orders` status is set to `waiting_for_refund`.
2. **Admin reviews the request** – sees details in `admin/refund-requests/show.blade.php`, can approve/reject, set approved amount, upload proof, and add internal notes.
3. **Status updates** – `RefundRequest` status moves through `pending`, `under_review`, `approved`, `rejected`.

## Identified Pain Points
| Area | Issue | Impact |
|------|-------|--------|
| Authorization | Manual `abort(403)` checks in controllers | Repetitive, error‑prone, hard to maintain |
| UI/UX – Client | No clear timeline or status view for the client after submission | Clients cannot track progress easily |
| UI/UX – Admin | Refund list lacks filtering/sorting, making it hard to prioritize large volumes | Admins spend extra time locating requests |
| Notifications | No push/email notification when a refund is approved/rejected | Delays client awareness |
| Automation | All refunds require manual admin action, even for low‑value cases | Inefficient, higher workload |
| Reporting | Refund data not integrated into existing financial reports | Missing insight for business decisions |

## Recommended Improvements
### 1. Centralize Authorization with Policies
- Create `RefundRequestPolicy` with `view`, `update`, `delete` methods.
- Register the policy in `AuthServiceProvider`.
- Replace manual `abort(403)` checks with `$this->authorize('view', $refundRequest);` etc.

### 2. Client‑Side Refund Timeline
- Add a new Blade view `client/refund-requests/timeline.blade.php`.
- Use the existing `TimelineService` (or extend it) to show events: request submitted, under review, approved/rejected, proof uploaded.
- Link from `client/refund-requests/show.blade.php` to the timeline.

### 3. Admin Refund Dashboard
- Create a dedicated page `admin/refund-requests/index.blade.php` with a table.
- Add filters: status, amount range, date, assigned admin.
- Use Laravel’s built‑in pagination and sortable columns (e.g., `spatie/laravel-query-builder`).

### 4. Notification Enhancements
- **Email**: Dispatch `RefundApproved` and `RefundRejected` mailables.
- **FCM**: Re‑use the existing FCM infrastructure to push a notification to the client when the status changes.
- Hook these into the `RefundRequest` model events (`updated`).

### 5. Automated Small Refunds
- Introduce a config value `refunds.auto_approve_limit` (e.g., $20).
- In the admin controller, if `amount_requested <= limit` and the request passes basic validation, automatically set status to `approved` and record the approved amount.
- Log the auto‑approval for audit purposes.

### 6. Reporting Integration
- Extend `FinancialReportController` to include a `refunds` section.
- Aggregate total refunded amount, average refund time, and refunds per admin.
- Add a chart (e.g., using Chart.js) to the admin dashboard.

### 7. Test Coverage
- Add feature tests for:
  - Client creating a refund request (validation, status change).
  - Admin approving/rejecting a request.
  - Policy enforcement (unauthorized access returns 403).
  - Notification dispatch (use `Mail::fake()` and `Notification::fake()`).

## Implementation Plan
1. Generate `RefundRequestPolicy` via `vendor\bin\sail artisan make:policy RefundRequestPolicy --model=RefundRequest`.
2. Update controllers to use `$this->authorize()`.
3. Build the client timeline view and extend `TimelineService` if needed.
4. Create admin dashboard view and route (`admin.refund-requests.index`).
5. Add notification listeners for `RefundRequest` model events.
6. Add config entry and auto‑approval logic in the admin controller.
7. Extend financial report queries and Blade view.
8. Write the new tests and run the suite.
9. Run `vendor\bin\sail bin pint --dirty` to format the new code.

---
*All suggested changes follow the existing code conventions and use Laravel 12 best practices.*
