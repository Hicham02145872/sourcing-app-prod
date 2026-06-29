## MODIFIED Requirements

### Requirement: Admin shipping fee edit shows Direct (China) and Indirect (Dubai) prices
**This requirement is modified to include split cost fields for indirect shipping.**
The admin shipping fee edit page SHALL display two price columns per item row: "Direct (China)" and "Indirect (Dubai)". The Indirect (Dubai) column SHALL contain two vertically-stacked input fields: "China → Dubai" and "Dubai → Africa".

#### Scenario: Admin views edit page for a country with shipping fees
- **WHEN** an admin opens the shipping fee edit page for a country
- **THEN** each item row in every transport tab (air/sea/train) SHALL show a "Direct (China)" price column and an "Indirect (Dubai)" price column
- **AND** the Indirect (Dubai) column SHALL contain two vertically-stacked inputs labeled "China → Dubai" and "Dubai → Africa"
- **AND** the "China → Dubai" input SHALL be bound to `price_per_kg_china_to_dubai`
- **AND** the "Dubai → Africa" input SHALL be bound to `price_per_kg_dubai_to_africa`
- **WHEN** only one price is set
- **THEN** the empty column SHALL display an empty input field

### Requirement: Admin can save both prices per item
**This requirement is modified to include the two new split cost fields.**
The save operation SHALL persist `price_per_kg` (Direct/China), `price_per_kg_dubai` (Indirect/Dubai total), `price_per_kg_china_to_dubai`, and `price_per_kg_dubai_to_africa` values.

#### Scenario: Admin sets all prices and saves
- **WHEN** an admin fills all price fields (Direct China, Indirect Dubai total, China→Dubai, Dubai→Africa) for an item and clicks save
- **THEN** all four values SHALL be stored in the `shipping_fee_items` table
- **WHEN** an admin leaves one of the split cost fields empty
- **THEN** that field SHALL be stored as NULL

### Requirement: Client displays Dubai price for UAE hub items
**This requirement is modified to include split cost display on the partner page.**
The client-side shipping fees list SHALL display `price_per_kg_dubai` for items shown under the UAE hub (train) tab, falling back to `price_per_kg` when the Dubai price is not set. The partner page SHALL display the split costs as two vertically-stacked fields.

#### Scenario: Client views UAE hub tab with Dubai prices
- **WHEN** a client views the UAE hub tab for a country
- **AND** the item has a `price_per_kg_dubai` value
- **THEN** the client SHALL display the Dubai price
- **WHEN** the item has no `price_per_kg_dubai` value
- **THEN** the client SHALL fall back to displaying `price_per_kg`

#### Scenario: Partner page shows split indirect costs
- **WHEN** a client visits the partner page ("Nos partenaires") for a country with indirect shipping
- **THEN** the page SHALL display `price_per_kg_china_to_dubai` labeled "China → Dubai" as the first cost field
- **AND** display `price_per_kg_dubai_to_africa` labeled "Dubai → [Country Name]" as the second cost field
- **AND** both fields SHALL be stacked vertically
- **AND** the currency SHALL be shown from the country's shipping fee configuration

## ADDED Requirements

### Requirement: Database migration for split cost columns
The `shipping_fee_items` table SHALL have two new nullable decimal columns for split indirect costs.

#### Scenario: Migration adds new columns
- **WHEN** the migration is run
- **THEN** `price_per_kg_china_to_dubai` (decimal 10,2 nullable) SHALL be added to `shipping_fee_items`
- **AND** `price_per_kg_dubai_to_africa` (decimal 10,2 nullable) SHALL be added to `shipping_fee_items`
- **AND** both columns SHALL be added to the `$fillable` array in `ShippingFeeItem` model
