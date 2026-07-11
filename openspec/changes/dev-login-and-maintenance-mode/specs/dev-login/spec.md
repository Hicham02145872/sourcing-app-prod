## ADDED Requirements

### Requirement: Developer login route accessible at /dev/login
The system SHALL provide a dedicated login route at `/{locale}/dev/login` for developers only.

#### Scenario: Developer accesses dev login page
- **WHEN** a user navigates to `/eng/dev/login`
- **THEN** the system SHALL display a login form with a distinct visual identity (dark theme, "DEV" badge)
- **AND** the form SHALL contain email and password fields
- **AND** the form SHALL NOT be accessible to non-developer users

#### Scenario: Non-developer cannot see dev login
- **WHEN** a non-developer user navigates to `/dev/login`
- **THEN** the system SHALL redirect to the standard login page

### Requirement: Dev login authenticates only developer role
The dev login form SHALL only accept credentials for users with the `developer` role.

#### Scenario: Developer logs in successfully
- **WHEN** a developer submits valid credentials on the dev login form
- **THEN** the system SHALL authenticate the user
- **AND** redirect to the Dev Dashboard at `/admin/dev-dashboard`
- **AND** set the user locale from the URL segment

#### Scenario: Non-developer tries to log in via dev login
- **WHEN** a user with role `client`, `admin`, or `super_admin` submits credentials on the dev login form
- **THEN** the system SHALL reject the login with an error message "Accès non autorisé"
- **AND** SHALL NOT authenticate the user

#### Scenario: Invalid credentials on dev login
- **WHEN** a developer submits invalid email or password on the dev login form
- **THEN** the system SHALL display a generic "Identifiants incorrects" error
- **AND** SHALL NOT reveal whether the email exists

### Requirement: Dev login rate limiting
The dev login endpoint SHALL be rate-limited to prevent brute force attacks.

#### Scenario: Too many failed attempts
- **WHEN** more than 5 failed login attempts occur from the same IP within 1 minute
- **THEN** the system SHALL return a 429 Too Many Requests response
