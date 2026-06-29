## ADDED Requirements

### Requirement: Split indirect delivery cost fields on partner page
The partner page ("Nos partenaires") SHALL display the indirect delivery cost as two separate vertically-stacked fields instead of a single grouped price.

#### Scenario: Partner page displays China→Dubai and Dubai→Africa costs
- **WHEN** a client views the partner page for a country that has indirect (Dubai) shipping
- **THEN** the page SHALL show two distinct price fields stacked vertically
- **AND** the first (top) field SHALL display the China → Dubai shipping cost
- **AND** the second (bottom) field SHALL display the Dubai → [destination country] shipping cost
- **AND** both fields SHALL show the currency code from the country's shipping fee configuration

#### Scenario: Both split costs are displayed with labels
- **WHEN** both `price_per_kg_china_to_dubai` and `price_per_kg_dubai_to_africa` are set for a shipping item
- **THEN** the first field SHALL be labeled "China → Dubai" and show the `price_per_kg_china_to_dubai` value
- **AND** the second field SHALL be labeled "Dubai → [Country Name]" and show the `price_per_kg_dubai_to_africa` value

### Requirement: Admin edit page supports split indirect cost inputs
The admin shipping fee edit page SHALL provide two additional input fields per item row for the split indirect costs, visually grouped under the existing "Indirect (Dubai)" column.

#### Scenario: Admin can input split indirect costs
- **WHEN** an admin opens the shipping fee edit page for a country
- **THEN** each item row SHALL show two vertically-stacked inputs within the Indirect (Dubai) column
- **AND** the first input SHALL be labeled "China → Dubai" bound to `price_per_kg_china_to_dubai`
- **AND** the second input SHALL be labeled "Dubai → Africa" bound to `price_per_kg_dubai_to_africa`

#### Scenario: Admin can save split indirect costs
- **WHEN** an admin fills both split cost fields and clicks save
- **THEN** both `price_per_kg_china_to_dubai` and `price_per_kg_dubai_to_africa` SHALL be persisted in the `shipping_fee_items` table
- **AND** either field MAY be left empty and stored as NULL

### Requirement: API responses include split cost fields
The shipping fee API endpoints SHALL return the new split cost fields alongside existing price data.

#### Scenario: API returns split costs for indirect shipping
- **WHEN** a client calls `getShippingFee()` or `getRatesForPopup()` for a country with indirect shipping
- **THEN** the response SHALL include `price_per_kg_china_to_dubai` and `price_per_kg_dubai_to_africa` fields
- **AND** the existing `price_per_kg_dubai` field SHALL remain for backward compatibility
