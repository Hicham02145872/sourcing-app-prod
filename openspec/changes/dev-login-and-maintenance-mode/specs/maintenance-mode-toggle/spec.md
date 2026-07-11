## ADDED Requirements

### Requirement: Dev Dashboard toggle for maintenance mode
The Dev Dashboard SHALL display a toggle switch to activate/deactivate maintenance mode, with a text field for a custom message.

#### Scenario: Developer activates maintenance mode
- **WHEN** a developer toggles the maintenance mode switch to "ON" in the Dev Dashboard
- **AND** optionally enters a custom message (e.g., "Maintenance en cours, revenez bientôt")
- **THEN** the system SHALL store the maintenance state and message in the cache
- **AND** all non-developer users SHALL immediately see the maintenance screen

#### Scenario: Developer deactivates maintenance mode
- **WHEN** a developer toggles the maintenance mode switch to "OFF"
- **THEN** the system SHALL remove the maintenance state from the cache
- **AND** all users SHALL regain normal access to the application

#### Scenario: Developer sees current maintenance status
- **WHEN** a developer opens the Dev Dashboard
- **THEN** the toggle SHALL reflect the current state (ON/OFF)
- **AND** the custom message field SHALL show the current message if set

### Requirement: Maintenance screen displayed to non-developer users
When maintenance mode is active, all non-developer users SHALL see a maintenance screen instead of the application.


#### Scenario: Non-developer user visits homepage during maintenance
- **WHEN** maintenance mode is active
- **AND** a non-developer user (or guest) navigates to any page
- **THEN** the system SHALL display a full-screen maintenance page with:
  - A modern glassmorphism card centered on screen with frosted glass effect
  - An animated SVG illustration (gear/cog animation or rocket launch) with smooth CSS keyframe animations
  - Floating particle or bokeh background animation using CSS (no heavy JS library)
  - The custom message in large bold typography (or default "Site en maintenance")
  - A subtle countdown or pulsing dot animation to indicate activity
  - The SmartSourcing logo with a gentle glow or shimmer effect
  - Responsive design: works on mobile, tablet, and desktop
  - Dark theme with gradient background (deep purple/blue to black)
  - Smooth fade-in entrance animation on page load
- **AND** the HTTP status code SHALL be 503

#### Scenario: Developer user is not affected by maintenance mode
- **WHEN** maintenance mode is active
- **AND** a developer user navigates to any page
- **THEN** the system SHALL NOT display the maintenance screen
- **AND** the developer SHALL access the application normally

#### Scenario: Admin and super_admin users are not affected by maintenance mode
- **WHEN** maintenance mode is active
- **AND** an admin or super_admin user navigates to any page
- **THEN** the system SHALL NOT display the maintenance screen
- **AND** the admin SHALL access the application normally

### Requirement: Maintenance mode does not affect API endpoints
The maintenance mode SHALL only apply to web routes, not to API or health check endpoints.

#### Scenario: Health check endpoint stays available
- **WHEN** maintenance mode is active
- **AND** a request hits `/health` or similar health check endpoint
- **THEN** the system SHALL respond normally (not 503)

### Requirement: Maintenance state persisted across cache clears
The maintenance state SHALL survive cache clears and application restarts.

#### Scenario: Cache is cleared while maintenance is active
- **WHEN** a developer runs `cache:clear` while maintenance mode is active
- **THEN** the maintenance mode SHALL remain active
- **AND** the custom message SHALL be preserved
