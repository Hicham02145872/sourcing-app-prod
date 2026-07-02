# sourcing-routing-popup Specification

## Purpose
Verify and correct the China/Dubai shipping routing popup logic displayed to clients when creating a sourcing request, ensuring correct pricing fields are used for direct and indirect shipping routes.

## Requirements

### Requirement: Direct shipping shows correct price_per_kg for non-UAE items
The direct shipping option in the routing popup SHALL display `price_per_kg` for items that are NOT classified as UAE hub items.

#### Scenario: Direct route shows pricing for standard items
- **WHEN** a client opens the routing popup for a country with shipping fees
- **AND** selects the "Direct Shipping" route
- **THEN** the displayed items SHALL exclude items whose `item_style` contains "dubai", "uae", "emirates", or "united arab"
- **AND** the price displayed SHALL be the `price_per_kg` value

### Requirement: Indirect shipping (via Dubai) shows combined split pricing
The indirect shipping option SHALL correctly reflect the Dubai hub routing, using the sum of `price_per_kg_china_to_dubai` + `price_per_kg_dubai_to_africa` when both are available, falling back to `price_per_kg` when split fields are null.

#### Scenario: Indirect route with split pricing available
- **WHEN** a client selects the "Indirect Shipping (via Dubai)" route
- **AND** an item has both `price_per_kg_china_to_dubai` and `price_per_kg_dubai_to_africa` values
- **THEN** the displayed price SHALL be the sum of both split fields
- **AND** the display SHALL indicate it includes China-to-Dubai and Dubai-to-Africa components

#### Scenario: Indirect route falls back to price_per_kg
- **WHEN** a client selects the "Indirect Shipping (via Dubai)" route
- **AND** an item has null values for both split fields
- **THEN** the displayed price SHALL fall back to `price_per_kg`

#### Scenario: Indirect route includes UAE hub items from any transport type
- **WHEN** the indirect route data is built
- **THEN** it SHALL include items from the `train` transport type
- **AND** SHALL also include items whose `item_style` matches UAE hub keywords from the selected transport type
- **AND** duplicates SHALL be removed (unique by ID)

### Requirement: Selected route updates sourcing_location field
When the client confirms the routing popup, the selected route SHALL update the `sourcing_location` select field in the main form.

#### Scenario: Client confirms China route
- **WHEN** a client clicks "Confirm & Submit" with "Direct Shipping" selected
- **THEN** the `sourcing_location` field in the form SHALL be set to `"china"`

#### Scenario: Client confirms Dubai route
- **WHEN** a client clicks "Confirm & Submit" with "Indirect Shipping (via Dubai)" selected
- **THEN** the `sourcing_location` field in the form SHALL be set to `"dubai"`
