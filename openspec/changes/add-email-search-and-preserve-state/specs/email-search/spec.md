## ADDED Requirements

### Requirement: Admin sourcing requests list searches by client email
The admin sourcing requests index SHALL search by client name AND email when a search term is entered.

#### Scenario: Search sourcing requests by client email
- **WHEN** admin enters a client email in the search bar on the sourcing requests list
- **THEN** the list SHALL display sourcing requests belonging to clients whose email matches the search term

### Requirement: Admin sourcing orders list searches by client email
The admin sourcing orders index SHALL search by client name AND email when a search term is entered.

#### Scenario: Search sourcing orders by client email
- **WHEN** admin enters a client email in the search bar on the sourcing orders list
- **THEN** the list SHALL display sourcing orders belonging to clients whose email matches the search term

### Requirement: Admin quotations list searches by client email
The admin quotations index SHALL search by client name AND email when a search term is entered.

#### Scenario: Search quotations by client email
- **WHEN** admin enters a client email in the search bar on the quotations list
- **THEN** the list SHALL display quotations belonging to clients whose email matches the search term

### Requirement: Admin users list uses consistent contains-match search
The admin users index SHALL use `LIKE '%search%'` (contains) instead of `LIKE 'search%'` (prefix-only) for consistency with all other search controllers.

#### Scenario: Search users by partial name
- **WHEN** admin enters a partial name in the search bar on the users list
- **THEN** the list SHALL display users whose name contains the search term

#### Scenario: Search users by partial email
- **WHEN** admin enters a partial email in the search bar on the users list
- **THEN** the list SHALL display users whose email contains the search term

### Requirement: Client sourcing requests handling list searches by email
The client sourcing requests handling view SHALL search by product name AND client email when a search term is entered.

#### Scenario: Client searches own requests by email
- **WHEN** a client enters their email in the search bar on the handling list
- **THEN** the list SHALL display their sourcing requests matching the search term (searches product_name and the client's own email)

### Requirement: Client dashboard searches by email
The client dashboard SHALL search by product name AND client email when a search term is entered.

#### Scenario: Client searches own dashboard by email
- **WHEN** a client enters their email in the search bar on the dashboard
- **THEN** the dashboard SHALL display their sourcing requests matching the search term (searches product_name and the client's own email)

### Requirement: Admin refund requests list has text search
The admin refund requests index SHALL support text search by shared_id, client name, and client email.

#### Scenario: Search refund requests by shared_id
- **WHEN** admin enters a shared_id in the search bar on the refund requests list
- **THEN** the list SHALL display refund requests whose shared_id matches the search term

#### Scenario: Search refund requests by client email
- **WHEN** admin enters a client email in the search bar on the refund requests list
- **THEN** the list SHALL display refund requests belonging to clients whose email matches the search term
