## ADDED Requirements

### Requirement: Admin shipping fee edit shows Direct (China) and Indirect (Dubai) prices
The admin shipping fee edit page SHALL display two price columns per item row: "Direct (China)" and "Indirect (Dubai)".

#### Scenario: Admin views edit page for a country with shipping fees
- **WHEN** an admin opens the shipping fee edit page for a country
- **THEN** each item row in every transport tab (air/sea/train) SHALL show a "Direct (China)" price column and an "Indirect (Dubai)" price column
- **WHEN** only one price is set
- **THEN** the empty column SHALL display an empty input field

### Requirement: Admin can save both prices per item
The save operation SHALL persist both `price_per_kg` (Direct/China) and `price_per_kg_dubai` (Indirect/Dubai) values.

#### Scenario: Admin sets both prices and saves
- **WHEN** an admin fills both "Direct (China)" and "Indirect (Dubai)" prices for an item and clicks save
- **THEN** both values SHALL be stored in the `shipping_fee_items` table
- **WHEN** an admin leaves one field empty
- **THEN** that field SHALL be stored as NULL

### Requirement: Client displays Dubai price for UAE hub items
The client-side shipping fees list SHALL display `price_per_kg_dubai` for items shown under the UAE hub (train) tab, falling back to `price_per_kg` when the Dubai price is not set.

#### Scenario: Client views UAE hub tab with Dubai prices
- **WHEN** a client views the UAE hub tab for a country
- **AND** the item has a `price_per_kg_dubai` value
- **THEN** the client SHALL display the Dubai price
- **WHEN** the item has no `price_per_kg_dubai` value
- **THEN** the client SHALL fall back to displaying `price_per_kg`
