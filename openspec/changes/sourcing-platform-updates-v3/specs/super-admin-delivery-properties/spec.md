## ADDED Requirements

### Requirement: Super Admin can manage delivery properties/benefits
The Super Admin dashboard SHALL provide a CRUD interface for managing custom delivery properties and benefits displayed in client-facing popups.

#### Scenario: Super Admin views delivery properties list
- **WHEN** a Super Admin navigates to the delivery properties management page
- **THEN** they SHALL see a list of all existing delivery properties with title, delivery type (direct/indirect), and active status

#### Scenario: Super Admin creates a delivery property
- **WHEN** a Super Admin clicks "Add Property" and fills in title, description, delivery type, and optional icon
- **THEN** the new property SHALL be stored in the `delivery_properties` table
- **AND** it SHALL immediately appear in client-facing delivery popups for the configured delivery type

#### Scenario: Super Admin edits or deletes a delivery property
- **WHEN** a Super Admin edits a delivery property's title or description
- **THEN** the changes SHALL be saved and immediately reflected in client-facing popups
- **WHEN** a Super Admin deletes a delivery property
- **THEN** the property SHALL be removed from the database and no longer appear in client popups

### Requirement: Super Admin can manage logistics defects/limitations
The Super Admin dashboard SHALL provide a CRUD interface for managing custom logistics defects and limitations displayed in the client delivery journey.

#### Scenario: Super Admin adds a logistics defect
- **WHEN** a Super Admin adds a defect/limitation with title and description (e.g., "African cargo does not ship batteries or liquids")
- **THEN** the defect SHALL be stored in the `delivery_defects` table with the specified delivery type
- **AND** it SHALL be displayed to the client in the relevant delivery path (direct or indirect)

#### Scenario: Super Admin can activate/deactivate defects
- **WHEN** a Super Admin toggles the active status of a defect
- **THEN** only active defects SHALL be displayed to clients
- **AND** inactive defects SHALL remain in the database for future reactivation

### Requirement: Super Admin can edit the discharge notice text
The Super Admin dashboard SHALL provide a text editor for the discharge notice displayed to clients.

#### Scenario: Super Admin updates discharge notice
- **WHEN** a Super Admin edits the discharge notice text in the dashboard
- **THEN** the updated text SHALL be saved to the `delivery_notices` table
- **AND** all client-facing interfaces displaying the discharge notice SHALL show the updated text without any code deployment
