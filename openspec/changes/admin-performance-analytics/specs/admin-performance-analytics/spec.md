## ADDED Requirements

### Requirement: Every status transition is recorded
The system SHALL persist a `request_status_logs` row for every sourcing request status change, recording the previous status, the new status, the acting user (when known) and the transition time. The log SHALL start collecting from the moment the feature is deployed.

#### Scenario: Transition creates a log entry
- **WHEN** a sourcing request changes status from `pending` to `in_review` by an admin
- **THEN** a `request_status_logs` row is created with `from_status = pending`, `to_status = in_review`, `changed_by_user_id` set to that admin and `changed_at` set to the transition moment

#### Scenario: System transitions are logged without a user
- **WHEN** a sourcing request changes status without an acting user
- **THEN** a row is created with `changed_by_user_id` null

### Requirement: Super admin sees per-admin analytics over a period
The system SHALL expose an analytics view (super admin only) aggregating, per admin and for a selectable date range: number of requests taken into review (`in_review`), number of responses (`quoted`), number of acceptances (`accepted`), acceptance rate and average response time from `in_review` to `quoted`.

#### Scenario: Aggregates respect the selected period
- **WHEN** a super admin requests analytics for the range 2026-09-01 to 2026-09-07
- **THEN** only transitions logged within that range are counted
- **AND** each admin row shows review count, response count, acceptance count, acceptance rate and average response time

#### Scenario: Acceptance rate is null when no responses
- **WHEN** an admin has reviews but zero `quoted` transitions in the period
- **THEN** the acceptance rate is displayed as not available

### Requirement: Reusable date-range filter
The system SHALL provide a reusable date-range (from/to) filter control usable on analytics and list views.

#### Scenario: Applying a date range filters the data
- **WHEN** a super admin sets a `from` and `to` date and applies the filter
- **THEN** the displayed data is restricted to that range

#### Scenario: Resetting the date range
- **WHEN** a super admin resets the date range
- **THEN** the date constraints are cleared and the full dataset is shown

#### Scenario: Feature flag hides the analytics page
- **WHEN** the feature flag `admin_performance_analytics` is not enabled
- **THEN** the analytics page is not accessible (404)
- **AND** the date-range controls remain hidden