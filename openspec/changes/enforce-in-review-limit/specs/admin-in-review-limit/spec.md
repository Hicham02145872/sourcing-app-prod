## ADDED Requirements

### Requirement: Admin can hold at most five in-review requests at once
The system SHALL enforce a maximum of 5 requests in `in_review` status assigned to a single admin. A request to move an additional request to `in_review` (claim from `pending` or any other transition into `in_review`) SHALL be rejected with HTTP 422 and error code `WORKFLOW_LIMIT_REACHED` when the admin already holds 5 in-review requests. Super admins SHALL be exempt. The rule SHALL only apply while the feature flag `workflow_in_review_limit` is enabled.

#### Scenario: Admin below the limit can claim a request
- **WHEN** an admin (role `admin`) with 4 in-review requests claims a `pending` request and moves it to `in_review`
- **THEN** the transition succeeds
- **AND** the admin now holds 5 in-review requests

#### Scenario: Admin at the limit is blocked
- **WHEN** an admin (role `admin`) already holds 5 in-review requests and attempts to move a 6th request to `in_review`
- **THEN** the transition is rejected
- **AND** the API/Livewire response has HTTP status 422
- **AND** the error code equals `WORKFLOW_LIMIT_REACHED`
- **AND** the request remains in its previous status

#### Scenario: Super admin is exempt from the limit
- **WHEN** a `super_admin` already holds 5 or more in-review requests and claims another request
- **THEN** the transition succeeds

#### Scenario: Rule disabled by feature flag
- **WHEN** the feature flag `workflow_in_review_limit` is not enabled
- **AND** an admin already holds 5 in-review requests and attempts to move a 6th request to `in_review`
- **THEN** the transition succeeds

#### Scenario: Limit applies to the assigning admin only
- **WHEN** admin A holds 5 in-review requests
- **AND** admin B (with fewer than 5) claims a request through a super-admin reassignment to admin B
- **THEN** the transition succeeds for admin B

### Requirement: Blocking UX follows the persistent banner pattern
The system SHALL present the in-review limit using the Meta Ads account-restriction pattern: an alert banner with a direct action button leading to resolution. The blocking modal SHALL include a redirect button "Voir mes demandes en attente" that takes the admin straight to the filtered list of their in-review requests (`/admin/requests?status=in_review&admin_id=me`). A persistent banner SHALL stay visible at the top of the admin dashboard on every connection while the admin holds 5 or more `in_review` requests, bearing the same redirect button. An existing persistent alert component (system banner, dashboard alert partial, etc.) SHALL be reused or extended instead of creating a redundant component.

#### Scenario: Blocking modal offers a direct redirect button
- **WHEN** a request to move a 6th request to `in_review` is rejected with `WORKFLOW_LIMIT_REACHED`
- **THEN** a modal is shown with the limit message
- **AND** the modal contains a button labeled "Voir mes demandes en attente"
- **AND** clicking the button navigates to `/admin/requests?status=in_review&admin_id=me`
- **AND** the page shows only the admin's current in-review requests so they can price them immediately

#### Scenario: Banner persists across sessions while at/over the limit
- **WHEN** an admin holds 5 or more `in_review` requests and opens the admin dashboard
- **THEN** an alert banner remains visible at the top of the dashboard
- **AND** the banner shows the same "Voir mes demandes en attente" button redirecting to the filtered in-review list

#### Scenario: Banner disappears when the admin drops below the limit
- **WHEN** an admin prices or releases one of their in-review requests so the count falls below 5
- **THEN** the persistent banner is no longer displayed on subsequent dashboard views

#### Scenario: Existing banner component is reused
- **WHEN** the application already provides a persistent alert/banner component
- **THEN** the in-review banner is rendered through that component rather than a new standalone mechanism