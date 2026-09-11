## 1. Configuration

- [x] 1.1 Create `config/fsb.php` with `workflow.in_review_limit` (default 5) and related keys
- [x] 1.2 Load `fsb` config in bootstrap (config file auto-loaded by Laravel)

## 2. Garde applicative

- [x] 2.1 Create `App\Exceptions\WorkflowLimitReachedException` carrying error code `WORKFLOW_LIMIT_REACHED`
- [x] 2.2 Create `App\Services\AdminSourcingWorkflowGuard` with `assertCanStartReview(SourcingRequest $request, User $admin): void` (counts in_review per admin, throws when >= limit, super_admin exempt, flag `workflow_in_review_limit` gating)
- [x] 2.3 Wrap the check in a DB transaction with `lockForUpdate` on the target request to prevent double-claim races

## 3. Intégration Livewire

- [x] 3.1 In `SourcingRequestWorkflow::updateStatus()`, call the guard when `$status === 'in_review'` before `transitionTo()` (for the acting admin, not super_admin)
- [x] 3.2 Catch `WorkflowLimitReachedException` → dispatch `show-error-toast` with bilingual message (fr/ar) and a `code` on the response
- [x] 3.3 Ensure no regression: non-`in_review` transitions and super-admin transitions keep existing behavior

## 4. Tests

- [x] 4.1 Feature test: admin with 4 in_review claims a 5th → success
- [x] 4.2 Feature test: admin with 5 in_review blocked on 6th → 422 + `WORKFLOW_LIMIT_REACHED`
- [x] 4.3 Feature test: super admin above limit → success
- [x] 4.4 Feature test: flag disabled → limit not enforced
- [x] 4.5 Verify existing suite still passes (no migration, no model contract change)

## 5. Bandeau persistant (pattern Meta Ads)

- [x] 5.1 Create reusable `admin.partials.workflow-alert-banner` (persistent banner + direct action button) and render it at the top of the admin dashboard
- [x] 5.2 Support `admin_id=me` self-filter on `admin.sourcing-requests.index` so the banner button opens the admin's own in-review list
- [x] 5.3 Feature tests: banner visible at the limit, hidden below the limit, `admin_id=me` filter narrows to the current admin
- [x] 5.4 Add `workflow.*` lang keys (en/fr/ar) for the banner and the blocking message