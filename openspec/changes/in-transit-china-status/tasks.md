## 1. Migration & modèle

- [x] 1.1 Create migration: add `china_tracking_number` (string nullable) and `package_label_photo_path` (string nullable) to `sourcing_orders`
- [x] 1.2 Add `in_transit_china` to `SourcingRequest::STATUSES`
- [x] 1.3 Extend `SourcingRequest::canTransitionTo()` map: `accepted` → `in_transit_china` (admin); `in_transit_china` → `completed`, `cancelled` (admin)
- [x] 1.4 Add `china_tracking_number` + `package_label_photo_path` to `SourcingOrder` `$fillable`

## 2. Action admin

- [x] 2.1 Add `markInTransit(SourcingRequest)` method to `SourcingRequestWorkflow` with validated photo upload (jpg/png/webp, max 5 MB) and `china_tracking_number` (required)
- [x] 2.2 Persist photo on `public` disk under `sourcing/in-transit/` and store path on the linked order
- [x] 2.3 Transition request to `in_transit_china` via `transitionTo()`; transition order only if its own validity rules allow it, otherwise keep order status and store data
- [x] 2.4 Add admin button in workflow blade (visible to admin/super_admin) completing lang keys fr/ar/eng

## 3. Affichage

- [x] 3.1 Show `in_transit_china` phase (translated status only) in client request detail, using existing `status_timestamps` flow — without China tracking number or photo
- [x] 3.2 Show the phase with China tracking and photo in super admin request/order views only (never in client views)

## 4. Tests

- [x] 4.1 Feature test: accepted → in_transit_china with photo+tracking succeeds; order data updated
- [x] 4.2 Feature test: invalid file (size/mime) rejected with validation error
- [x] 4.3 Feature test: unauthorized transition refused, status unchanged
- [x] 4.4 Feature test: client view renders the phase as status-only without tracking/photo; super admin view renders tracking + photo; renders correctly without photo
- [x] 4.5 Feature test: existing order transitions unchanged (regression guard)