## ADDED Requirements

### Requirement: Countries have a direct-shipping flag

The `countries` database table SHALL have an `is_direct` boolean column indicating whether shipments go directly from China to the destination country (true) or must pass through the Boai/Dubai hub (false). Default value SHALL be `false`.

#### Scenario: New country defaults to indirect

- **WHEN** a new country is created via the admin panel
- **THEN** the `is_direct` column SHALL default to `false`

### Requirement: Admin can toggle direct/indirect per country

The admin SHALL be able to set the `is_direct` flag for each country via the shipping fees edit page.

#### Scenario: Toggle is visible on shipping fee edit page

- **WHEN** a super admin navigates to the shipping fee edit page for any country
- **THEN** a toggle/switch labeled "Direct Shipping (China → Destination)" SHALL be displayed in the country details section

#### Scenario: Toggle persists on save

- **WHEN** an admin toggles the direct-shipping flag and clicks save
- **THEN** the `is_direct` column SHALL be updated in the database

### Requirement: Direct countries hide indirect pricing in admin edit

When a country has `is_direct = true`, the admin shipping fee edit page SHALL hide the "Indirect (Dubai)" column (price_per_kg_china_to_dubai, price_per_kg_dubai_to_africa inputs).

#### Scenario: Indirect column hidden for direct countries

- **WHEN** editing shipping fees for a country with `is_direct = true`
- **THEN** the "Indirect (Dubai)" column SHALL NOT be displayed in the rates table

#### Scenario: Indirect column visible for indirect countries

- **WHEN** editing shipping fees for a country with `is_direct = false`
- **THEN** the "Indirect (Dubai)" column SHALL be displayed as before

### Requirement: Direct countries hide indirect option in client routing popup

When a country has `is_direct = true`, the client shipping routing popup (shown when creating a sourcing request) SHALL only display the "Direct Shipping" option and hide the "Indirect Shipping (via Dubai)" option.

#### Scenario: Only direct option shown for direct countries

- **WHEN** a client creates a sourcing request for a destination with `is_direct = true`
- **THEN** the routing popup SHALL show only the "Direct Shipping" card

#### Scenario: Both options shown for indirect countries

- **WHEN** a client creates a sourcing request for a destination with `is_direct = false`
- **THEN** the routing popup SHALL show both "Direct Shipping" and "Indirect Shipping" cards

### Requirement: Direct countries hide Dubai pricing on shipping fees list page

When a country has `is_direct = true`, the client shipping fees list page SHALL NOT display Dubai-related pricing columns (China→Dubai, Dubai→Africa) for any transport mode tab.

#### Scenario: Dubai prices hidden for direct countries on client page

- **WHEN** a client views shipping fees for a country with `is_direct = true`
- **THEN** the "Dubai" related price rows/columns SHALL NOT be displayed

#### Scenario: Dubai prices shown for indirect countries on client page

- **WHEN** a client views shipping fees for a country with `is_direct = false`
- **THEN** the "Dubai" related price rows/columns SHALL be displayed as before

### Requirement: Direct countries skip indirect in popup rates API

When a country has `is_direct = true`, the `getRatesForPopup` endpoint SHALL return an empty `indirect` section.

#### Scenario: API returns empty indirect for direct countries

- **WHEN** the `GET /shipping-popup-rates/{country}` endpoint is called for a country with `is_direct = true`
- **THEN** the response SHALL contain `indirect.items` as an empty array
