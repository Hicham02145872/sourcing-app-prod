## ADDED Requirements

### Requirement: Admin quotation show view displays full delivery address
The admin quotation show page SHALL display the client's complete final delivery address alongside existing destination information.

#### Scenario: Quotation show shows delivery address per destination
- **WHEN** an admin views a quotation
- **THEN** the Destinations table SHALL include a column or field showing the full delivery address from `sourcing_request_destinations.address`
- **AND** if a `label_address` exists, it SHALL be displayed as the override address with a visual indicator

#### Scenario: No address is set shows placeholder
- **WHEN** a destination has no address and no label_address set
- **THEN** a placeholder text "No address provided" SHALL be displayed

### Requirement: Admin quotation edit view shows delivery address
The admin quotation edit page SHALL display the customer's delivery address in a read-only format for reference during pricing.

#### Scenario: Quotation edit shows address in product specs section
- **WHEN** an admin edits a quotation
- **THEN** the Product Specifications or Destinations section SHALL display the full delivery address for each destination
- **AND** the address SHALL be read-only (not editable in the quotation form)
