## ADDED Requirements

### Requirement: Admin captures a weight for each quality in a quotation

When creating or editing a quotation, the admin SHALL provide a weight value and a weight unit for EACH quality option (`low`, `medium`, `good`). The weight unit SHALL be one of `g`, `kg`, or `colis`. The weight value SHALL be required and numeric (>= 0).

#### Scenario: Create quotation with per-quality weights

- **WHEN** the admin fills the quotation create form and enters a distinct weight + unit for each quality option
- **THEN** the form submits successfully and the quotation stores a weight and unit for each of the `low`, `medium`, and `good` quality options

#### Scenario: Create quotation missing a quality weight

- **WHEN** the admin submits the quotation create form without a weight for at least one quality option
- **THEN** the quotation is not saved and the admin sees a validation error for the missing weight

#### Scenario: Create quotation with an invalid weight unit

- **WHEN** the admin submits a quality weight whose unit is not `g`, `kg`, or `colis`
- **THEN** the quotation is not saved and the admin sees a validation error for the invalid unit

### Requirement: Per-quality weight is persisted in the quality options

The system SHALL store the weight and its unit inside the `quality_options` JSON column of the quotation, keyed by quality. Each quality entry SHALL contain `weight` and `weight_unit` alongside `price`, `image_path`, and `image_paths`.

#### Scenario: Stored structure per quality

- **WHEN** a quotation is created or updated with per-quality weights
- **THEN** `quality_options['low']`, `quality_options['medium']`, and `quality_options['good']` each contain a `weight` value and a `weight_unit` value in `g`, `kg`, or `colis`

#### Scenario: Update quotation changes a quality weight

- **WHEN** the admin edits an existing quotation and changes the weight of one quality while keeping the others unchanged
- **THEN** only the edited quality's `weight` (and `weight_unit` if changed) is updated and the other qualities keep their values

### Requirement: Remove the order-level weight fields

The quotation SHALL no longer expose the global `unit_weight` / `weight_unit` columns. The create form validation SHALL NOT require `unit_weight` or `weight_unit`, and the quotation create/edit pages SHALL NOT show a single global weight field.

#### Scenario: Global weight field no longer present

- **WHEN** the admin opens the quotation create or edit page
- **THEN** there is no single "Unit Weight" field at the order level; the weight appears only inside each quality option block

#### Scenario: Submit without global weight

- **WHEN** the admin submits the quotation form without `unit_weight` / `weight_unit` data
- **THEN** the quotation is saved successfully and no weight columns are written

### Requirement: Existing quotations keep their weight after migration

The migration that removes the global weight columns SHALL first copy the existing `unit_weight` / `weight_unit` value into each quality option's `weight` / `weight_unit` so no quotation loses its weight data.

#### Scenario: Existing quotation with a global weight

- **WHEN** an existing quotation with `unit_weight` and `weight_unit` set is migrated
- **THEN** each of its `quality_options` entries contains the migrated weight and unit, and the global columns are dropped

#### Scenario: Existing quotation without weight

- **WHEN** an existing quotation has no `unit_weight` value
- **THEN** its quality options remain unchanged (no weight is fabricated) and the global columns are dropped

### Requirement: Display uses the selected quality's weight

All quotation and order display pages SHALL show the weight of the selected quality option instead of an order-level weight. When no quality is selected yet, the pages SHALL show the weight of a defined default quality (e.g., `medium`) or a dash when no weight exists.

#### Scenario: Order display with a selected quality

- **WHEN** a display page (admin quotation show, admin/client sourcing request, admin/client sourcing order) renders a quotation whose quality is selected
- **THEN** it displays the `weight` and `weight_unit` of the selected quality option

#### Scenario: Display with no selected quality

- **WHEN** a display page renders a quotation with no quality selected yet
- **THEN** it shows the weight of a default quality option (e.g., `medium`) or `-` when none is available

### Requirement: Client sees the weight of each quality when choosing a quality

The client-facing quality selector SHALL display the weight and its unit for every offered quality option, and the displayed weight SHALL update automatically when the client changes the selected quality.

#### Scenario: Quality cards show their weight

- **WHEN** the client opens a sourcing request that offers quality options, and each option carries a `weight` / `weight_unit`
- **THEN** the selector shows the weight (e.g. "250 g", "2 kg") on each quality card or button alongside the price

#### Scenario: Weight updates on selection change

- **WHEN** the client selects a different quality in the selector
- **THEN** the displayed Unit Weight updates to the selected quality's weight and unit without a page reload

#### Scenario: Quality without a weight

- **WHEN** a quality option has no `weight` stored
- **THEN** the selector displays a dash (`-`) for that weight instead of an error