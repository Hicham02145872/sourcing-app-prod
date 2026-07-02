# shipping-fees-filter Specification

## Purpose
Allow admins to filter the shipping fees countries table to display only countries that have at least one configured shipping fee item (with `price_per_kg` set), reducing visual noise from unconfigured countries.

## Requirements

### Requirement: Admin can toggle filter to show only configured countries
The shipping fees table SHALL provide a toggle/filter button that switches between showing all countries and showing only countries with at least one shipping fee item having a non-null `price_per_kg`.

#### Scenario: Admin clicks "Configured Only" button, list is filtered
- **WHEN** an admin clicks the "Configured Only" filter button on the shipping fees page
- **THEN** the table SHALL display only countries that have at least one `shippingFeeItem` with `price_per_kg IS NOT NULL`
- **AND** the filter button SHALL visually indicate it is active (e.g., highlighted or filled style)

#### Scenario: Admin clicks filter again to show all countries
- **WHEN** an admin clicks the active "Configured Only" filter button
- **THEN** the table SHALL display all countries (default unfiltered state)
- **AND** the filter button SHALL return to its inactive style

#### Scenario: Filter state is preserved during search
- **WHEN** an admin has the "Configured Only" filter active
- **AND** types a search term
- **THEN** the search SHALL be applied within the filtered set of configured countries only

#### Scenario: Filtered list is empty when no countries configured
- **WHEN** an admin clicks "Configured Only"
- **AND** no countries have any shipping fee items with `price_per_kg` set
- **THEN** the table SHALL show the empty state message ("No matching countries")
