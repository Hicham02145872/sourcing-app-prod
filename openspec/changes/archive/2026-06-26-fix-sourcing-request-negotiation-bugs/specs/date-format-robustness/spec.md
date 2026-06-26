## ADDED Requirements

### Requirement: Timestamp fields are Carbon instances
The `SourcingRequest` model SHALL cast `negotiated_at` and `accepted_at` as `datetime` so they return `Carbon\Carbon` instances instead of raw strings.

#### Scenario: Accessing negotiated_at after retrieval from DB
- **WHEN** a `SourcingRequest` with status `negotiating` is fetched from the database
- **THEN** `$sourcingRequest->negotiated_at` SHALL be a `Carbon\Carbon` instance
- **AND** `->format()` SHALL be callable on it

#### Scenario: Accessing accepted_at after retrieval from DB
- **WHEN** a `SourcingRequest` with status `accepted` is fetched from the database
- **THEN** `$sourcingRequest->accepted_at` SHALL be a `Carbon\Carbon` instance
- **AND** `->format()` SHALL be callable on it

#### Scenario: Null values remain null for non-applicable statuses
- **WHEN** a `SourcingRequest` has status `pending` (or any status before `negotiating`)
- **THEN** `$sourcingRequest->negotiated_at` SHALL be `null`
- **AND** `$sourcingRequest->accepted_at` SHALL be `null`

### Requirement: Views render safely when timestamps are null
All `->format()` calls on `negotiated_at` and `accepted_at` in Blade views SHALL use null-safe operator (`?->`) to prevent crashes on null values.

#### Scenario: Admin show view renders with null timestamps
- **WHEN** the admin show view renders a sourcing request where `negotiated_at` is `null`
- **THEN** the view SHALL NOT throw an error
- **AND** SHALL render an empty string or appropriate fallback

#### Scenario: Client show view renders with null timestamps
- **WHEN** the client show view renders a sourcing request where `negotiated_at` or `accepted_at` is `null`
- **THEN** the view SHALL NOT throw an error
- **AND** SHALL render an empty string or appropriate fallback

### Requirement: Client sourcing requests index redirects to handling
The `client.sourcing-requests.index` route SHALL redirect to `client.sourcing-requests.handling` instead of rendering a non-existent view.

#### Scenario: Client visits sourcing requests list
- **WHEN** an authenticated client visits `/client/sourcing-requests`
- **THEN** they SHALL be redirected to `/client/sourcing-requests/handling`
- **AND** SHALL NOT return a 500 error
