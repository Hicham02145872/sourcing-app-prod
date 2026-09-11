## ADDED Requirements

### Requirement: Track time elapsed in current status
The system SHALL store on each sourcing request a `status_changed_at` timestamp reflecting the moment the request entered its current status, updated on every status transition. The system SHALL store an `is_restricted_due_to_delay` boolean (default false) indicating the request exceeded its SLA deadline.

#### Scenario: Status transition updates the timestamp
- **WHEN** a sourcing request changes status
- **THEN** `status_changed_at` equals the transition moment
- **AND** `is_restricted_due_to_delay` is set to false

#### Scenario: Backfill for existing requests
- **WHEN** the migration runs on a request with an existing status
- **THEN** `status_changed_at` is populated from the request's recorded status timestamp when available, otherwise from the last update

### Requirement: SLA deadlines restrict overdue requests
The system SHALL enforce configurable deadlines per SLA status: 24 h for `in_review`, `quoted` and `negotiating`, and 48 h for `accepted`. When a request stays in a SLA status beyond its deadline, the system SHALL mark it `is_restricted_due_to_delay = true` and notify the assigned admin. The behavior SHALL be a no-op when the feature flag `sla_deadlines_autolock` is disabled.

#### Scenario: Request remains in review past the deadline
- **WHEN** a request stays in `in_review` for more than 24 hours
- **AND** the `workflow:check-deadlines` command runs
- **THEN** the request is marked `is_restricted_due_to_delay = true`
- **AND** the assigned admin receives an SLA deadline notification

#### Scenario: Non-SLA statuses are never restricted
- **WHEN** a request is in a status without an SLA configured (for example `pending`)
- **AND** the `workflow:check-deadlines` command runs
- **THEN** the request is not marked as restricted

#### Scenario: Restriction lifts on next transition
- **WHEN** a request marked `is_restricted_due_to_delay` changes status
- **THEN** `is_restricted_due_to_delay` is set to false
- **AND** `status_changed_at` is refreshed to the transition moment

#### Scenario: Feature flag disables SLA enforcement
- **WHEN** the feature flag `sla_deadlines_autolock` is not enabled
- **AND** a request is past its deadline
- **THEN** the `workflow:check-deadlines` command takes no action on that request

### Requirement: SLA blockages reuse the banner and redirect pattern
The system SHALL surface SLA overruns with the same persistent banner pattern used for the in-review limit: an alert banner plus a direct action button that redirects to the overdue list for the specific status. The banner SHALL remain visible at the top of the admin dashboard on every connection while the admin has at least one assigned request with `is_restricted_due_to_delay = true`, and SHALL grant resolution directly from the affected status list. An existing persistent alert component SHALL be reused or extended when available.

#### Scenario: Overdue request triggers a persistent banner
- **WHEN** an admin has an assigned request marked `is_restricted_due_to_delay = true`
- **THEN** an alert banner stays visible at the top of the admin dashboard on each connection
- **AND** the banner's action button redirects to the overdue list for the affected status (`/admin/requests?status=<status>&overdue=true`)

#### Scenario: Redirect targets the specific status
- **WHEN** the overdue request is in `negotiating`
- **THEN** the banner button navigates to `/admin/requests?status=negotiating&overdue=true`
- **AND** the page shows only the admin's overdue requests for that status so they can act on them immediately

#### Scenario: Banner clears once no request is overdue
- **WHEN** the admin prices or moves the overdue request so that no assigned request remains `is_restricted_due_to_delay`
- **THEN** the persistent banner is no longer displayed on subsequent dashboard views

#### Scenario: Same banner component as the in-review limit
- **WHEN** the in-review limit banner is rendered through the shared persistent banner component
- **THEN** the SLA overdue banner uses the same component with a status-specific redirect button