## ADDED Requirements

### Requirement: Quotation list ordered by newest first
The admin quotation list page SHALL display quotations sorted by creation date in descending order, with the most recently created quotation shown first.

#### Scenario: Quotation list loads
- **WHEN** an admin navigates to the quotation list page
- **THEN** the quotations SHALL be ordered by `created_at` descending
- **AND** the newest quotation SHALL appear at the top of the list
- **AND** the oldest quotation SHALL appear at the bottom

#### Scenario: Search preserves ordering
- **WHEN** an admin performs a search on the quotation list
- **THEN** the filtered results SHALL still be ordered by `created_at` descending
