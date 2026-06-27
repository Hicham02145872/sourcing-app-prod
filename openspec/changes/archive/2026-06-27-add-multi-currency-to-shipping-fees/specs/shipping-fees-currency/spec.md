## ADDED Requirements

### Requirement: Currency column in shipping fees index table
The admin shipping fees index table SHALL display the configured currency for each country in a dedicated column.

#### Scenario: Index table shows currency
- **WHEN** an admin views the shipping fees index page
- **THEN** each country row SHALL display the currency code (e.g., "USD", "EUR") from the associated shipping fee record
- **WHEN** a country has no shipping fee configured
- **THEN** the currency column SHALL display "—" or "USD" as default

### Requirement: Currency-aware Excel import
The Excel import form SHALL allow the admin to select a target currency before uploading, and imported shipping fees SHALL use that currency instead of hardcoded USD.

#### Scenario: Import with selected currency
- **WHEN** an admin selects "EUR" on the import form and uploads a valid Excel file
- **THEN** all new ShippingFee records created by the import SHALL have currency = "EUR"
- **WHEN** an admin imports without selecting a currency
- **THEN** the default "USD" SHALL be used

#### Scenario: Import updates existing records
- **WHEN** an existing ShippingFee record is updated by the import
- **THEN** its currency field SHALL NOT be overwritten (only new records use the selected currency)
