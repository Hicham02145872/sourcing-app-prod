# quotation-shipping-address Specification

## Purpose
Display the delivery address of each destination in the admin quotation creation form so that the admin can see the full shipping address when creating a quotation.

## Requirements

### Requirement: Admin sees delivery address in quotation create form
The quotation create page SHALL display the delivery address (`address` and `label_address`) for each destination in the "Destinations & Quantities" table.

#### Scenario: Admin opens create quotation page for a sourcing request with destinations
- **WHEN** an admin opens the create quotation page for a sourcing request
- **AND** the sourcing request has destinations with `address` values
- **THEN** the destinations table SHALL display the `address` field content in a new "Address" column for each destination row

#### Scenario: Destination has label_address
- **WHEN** a destination has a `label_address` value
- **THEN** the `label_address` SHALL be displayed alongside the `address` (e.g., as a subtitle or label)

#### Scenario: Destination has no address
- **WHEN** a destination has no `address` value
- **THEN** the address column SHALL display a placeholder text (e.g., "—" or "No address provided")
