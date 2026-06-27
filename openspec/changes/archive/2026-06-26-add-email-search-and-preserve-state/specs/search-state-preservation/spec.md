## ADDED Requirements

### Requirement: All paginated lists preserve search params across pages
Every paginated list view SHALL use `->withQueryString()` on the paginator so that search and filter parameters persist when navigating between pages.

#### Scenario: Navigate to page 2 with active search
- **WHEN** a user has entered a search term and clicks page 2 of the paginator
- **THEN** the page 2 results SHALL still be filtered by the original search term

#### Scenario: Navigate to page 2 with active status filter
- **WHEN** a user has selected a status filter and clicks page 2 of the paginator
- **THEN** the page 2 results SHALL still be filtered by the original status filter

### Requirement: Show/detail views preserve search state when returning to list
Every show/detail view's "back" link SHALL use `url()->previous()` (with a fallback default route) to return the user to the previous list page with all search/filter parameters intact.

#### Scenario: Navigate to show page and return via back link
- **WHEN** a user searches for "john" on the sourcing orders list, clicks an order to view it, then clicks the "back" link on the show page
- **THEN** the user SHALL be returned to the sourcing orders list with "john" still entered in the search field and the results still filtered

#### Scenario: Back link fallback when no previous URL exists
- **WHEN** a user navigates directly to a show page (e.g., by bookmark) and clicks the "back" link
- **THEN** the user SHALL be redirected to the default list route (e.g., admin.sourcing-orders.index) rather than an error or external URL
