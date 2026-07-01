## ADDED Requirements

### Requirement: Shipping fees pop-up on sourcing request creation
When a client creates a sourcing request and the `shipping_fees_popup` feature flag is set to `visible`, the system SHALL display a pop-up modal showing applicable shipping fees after the client selects a shipping method.

#### Scenario: Feature flag enabled — pop-up shown
- **WHEN** a client is on the sourcing request creation page
- **AND** the `shipping_fees_popup` feature flag status is `visible`
- **AND** the client selects a shipping method
- **THEN** the system SHALL display a pop-up modal with shipping fee details before form submission

#### Scenario: Feature flag disabled — no pop-up
- **WHEN** a client is on the sourcing request creation page
- **AND** the `shipping_fees_popup` feature flag status is `hidden`
- **THEN** the system SHALL NOT display the shipping fees pop-up
- **AND** the sourcing request creation flow SHALL proceed normally

### Requirement: Dev Dashboard control for shipping fees pop-up
The Dev Dashboard SHALL allow toggling the `shipping_fees_popup` feature flag between `visible`, `hidden`, and `coming_soon` states, matching the existing feature flag management UI.

#### Scenario: Admin toggles flag in Dev Dashboard
- **WHEN** an admin navigates to the Dev Dashboard
- **AND** toggles the `shipping_fees_popup` flag status
- **THEN** the change SHALL take effect immediately after cache is cleared
- **AND** the new status SHALL be reflected on the client sourcing request creation page

### Requirement: Pop-up displays shipping fee information
The pop-up modal SHALL display shipping fee details including destination countries, service types, estimated costs, and delivery timelines.

#### Scenario: Pop-up content loads
- **WHEN** the shipping fees pop-up is triggered
- **THEN** it SHALL fetch and display shipping fee data for the selected destinations and shipping method
- **AND** the client SHALL be able to review the fees and proceed or cancel
