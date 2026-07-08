## ADDED Requirements

### Requirement: Dedicated mail log channel

The system SHALL have a dedicated `mail` log channel configured in `config/logging.php` that writes mail-related errors to a separate file (`storage/logs/mail.log`) for operational monitoring.

#### Scenario: Mail transport failure is logged

- **WHEN** any mail transport exception is caught
- **THEN** the error is written to the `mail` log channel with level `error`
- **AND** includes context: recipient, notification/mailable class, exception message

### Requirement: Silent failure for unprotected synchronous Mail:: calls

Any direct `Mail::to()->send()`, `Mail::send()`, or legacy `Mail::` call that lacks try/catch protection MUST be wrapped so that mail transport exceptions are caught, logged, and execution continues without a 500 error.

Affected locations:
- `app/Models/User.php:142` — CustomVerifyEmail

#### Scenario: Email verification fails due to SMTP error

- **WHEN** a user registers and the `CustomVerifyEmail` mail send throws a transport exception
- **THEN** the exception is caught and logged to the `mail` channel
- **AND** the user registration completes successfully with a flash message indicating the verification email could not be sent
