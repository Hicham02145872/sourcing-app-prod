## ADDED Requirements

### Requirement: Shipping label is rendered as a 300 DPI PNG image
The system SHALL render the shipping label as a PNG image at 300 DPI, matching the visual layout of the existing PDF label (logo, main info table with Country / Seller Name / Order ID / Product Name / Quantity / Recipient Address, footer with contact details). The rendering SHALL rely only on the GD extension and TrueType fonts shipped with the project.

#### Scenario: Requesting the label as PNG
- **WHEN** an authorized user opens the admin shipping label with `?format=png`
- **THEN** the response has HTTP 200
- **AND** the `Content-Type` is `image/png`
- **AND** the body is a valid PNG with an A4 canvas at 300 DPI (2480 × 3508 pixels)

#### Scenario: Label content matches the order
- **WHEN** a label PNG is rendered for an order
- **THEN** it displays the order reference, seller name, product name, destination country, quantity and recipient address as stored (with `label_address` fallback to `address`)

#### Scenario: Per-destination label PNG
- **WHEN** an authorized user requests the per-destination label with `?format=png`
- **THEN** the response is a valid PNG image specific to that destination

#### Scenario: Long recipient address renders without breaking
- **WHEN** the recipient address is longer than a single line
- **THEN** the text is word-wrapped inside the cell
- **AND** the PNG is still generated without error

#### Scenario: Missing logo asset
- **WHEN** the logo file is missing
- **THEN** the label renders without the logo without failing

### Requirement: Default format stays PDF and is flag-gated
The system SHALL keep the PDF generation as the default behavior. The PNG format SHALL be available only while the feature flag `label_image_output` is enabled.

#### Scenario: No format parameter returns PDF
- **WHEN** an authorized user opens the shipping label route without a format parameter
- **THEN** the PDF download is returned as before

#### Scenario: PNG disabled by feature flag
- **WHEN** the feature flag `label_image_output` is disabled
- **AND** a user requests `?format=png`
- **THEN** the response falls back to the PDF format