## ADDED Requirements

### Requirement: Notifications with mail channel are queued

Every notification class that has `'mail'` in its `via()` method and uses the `Queueable` trait MUST also implement the `ShouldQueue` interface, so the actual mail sending is deferred to the queue worker and transport failures never affect the HTTP response.

The following notifications MUST implement `ShouldQueue`:
- `TrackingNumberAdded`
- `SourcingRequestStatusUpdated`
- `SourcingOrderStatusUpdated`
- `QuotationCreated`
- `FsbTrackingGenerated`
- `DevLaravelLogErrorAlert`
- `SourcingRequestAutoAssigned`
- `DevPingNotification`
- `RefundStatusUpdated`
- `AlternativeSourcingNotification`
- `ClientAccountCreated`
- `SourcingRequestCreated`
- `ClientRegisteredForAdmins`
- `QuotationAccepted`
- `QuotationRejected`
- `RefundRequestCreated`
- `ProofOfPaymentUploaded`
- `DossierAssignmentRemoved`
- `SourcingRequestAssigned`
- `AdminSourcingRequestStatusUpdated`
- `QuotationNegotiationRequested`
- `BaseAdminNotification` (abstract, affects all its children)

The `CustomVerifyEmail` notification is EXCLUDED from this requirement — it MUST remain synchronous.

#### Scenario: SMTP fails while sending a queued notification

- **WHEN** a user triggers an action that calls `$user->notify(new SourcingOrderStatusUpdated(...))`
- **AND** the SMTP server is unreachable at that moment
- **THEN** the notification job is dispatched to the `database` queue
- **AND** the HTTP response completes without error
- **AND** the queue worker retries the job (up to default max attempts)
- **AND** after all retries fail, the job's `failed()` method (if defined) runs

#### Scenario: SMTP fails while sending the email verification

- **WHEN** a new user registers and `CustomVerifyEmail` is sent synchronously
- **AND** the SMTP server is unreachable
- **THEN** the exception is caught and logged with recipient and error details
- **AND** the HTTP response completes without a 500 error

### Requirement: Unprotected synchronous mail calls are wrapped in try/catch

Every unprotected synchronous `Mail::send`, `Mail::to()->send()`, or `notify()` call that sends mail (and is NOT being queued) MUST be wrapped in a try/catch block that logs the error and continues execution.

The following locations currently lack try/catch and MUST be protected:
- `app/Models/User.php:142` — `CustomVerifyEmail` send
- `app/Http/Controllers/Admin/AdminSourcingRequestController.php:64` — `ClientAccountCreated`
- `app/Http/Controllers/Admin/SourcingOrderController.php:268` — `ProofOfPaymentRejected`
- `app/Http/Controllers/Admin/SourcingOrderController.php:538` — `TrackingNumberAdded`
- `app/Http/Controllers/Admin/QuotationController.php:277,502` — `AlternativeSourcingNotification`
- `app/Http/Controllers/Client/QuotationController.php:194,434` — `ProofOfPaymentUploaded`
- `app/Http/Controllers/Client/SourcingOrderController.php:113` — `ProofOfPaymentUploaded`
- `app/Http/Controllers/Client/RefundRequestController.php:148` — `RefundRequestCreated`
- `app/Livewire/Admin/DevDashboard.php:1234,1662` — dev notifications
- `app/Livewire/Admin/SourcingRequestCreate.php:149` — `ClientAccountCreated`
- `app/Livewire/Admin/SourcingOrderWorkflow.php:120` — `TrackingNumberAdded`
- `app/Observers/SourcingRequestObserver.php:49,57` — `DossierAssignmentRemoved`, `SourcingRequestAssigned`
- `app/Listeners/NotifyAdminsOfClientRegistration.php:23` — `ClientRegisteredForAdmins`

#### Scenario: Mail transport fails during transaction

- **WHEN** an HTTP request triggers any of the above `notify()` or `Mail::` calls
- **AND** the mail transport throws an exception
- **THEN** the exception is caught and logged at `error` level with context (file, line, recipient, notification type)
- **AND** the HTTP response completes normally (no 500 error)
- **AND** the primary business logic (order creation, proof upload, etc.) is NOT rolled back

### Requirement: Mail failures are logged to a dedicated channel

All caught mail transport exceptions MUST be logged with:
- Log level: `error`
- Context: recipient email, notification/mailable class name, exception message and trace
- Channel: `mail` (dedicated logging channel in `config/logging.php`)

#### Scenario: Admin monitors mail health

- **WHEN** a mail transport failure occurs and is caught
- **THEN** a log entry is written to `storage/logs/mail.log` (or configured `mail` channel)
- **AND** the log entry contains: timestamp, exception class, message, file:line, recipient
