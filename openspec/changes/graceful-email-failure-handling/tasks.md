## 1. Dedicated Mail Log Channel

- [ ] 1.1 Add `mail` log channel to `config/logging.php` (daily driver, `storage/logs/mail.log`)

## 2. Queue All Notifications That Send Mail

- [ ] 2.1 Add `implements ShouldQueue` to `TrackingNumberAdded`
- [ ] 2.2 Add `implements ShouldQueue` to `SourcingRequestStatusUpdated`
- [ ] 2.3 Add `implements ShouldQueue` to `SourcingOrderStatusUpdated`
- [ ] 2.4 Add `implements ShouldQueue` to `QuotationCreated`
- [ ] 2.5 Add `implements ShouldQueue` to `FsbTrackingGenerated`
- [ ] 2.6 Add `implements ShouldQueue` to `DevLaravelLogErrorAlert`
- [ ] 2.7 Add `implements ShouldQueue` to `SourcingRequestAutoAssigned`
- [ ] 2.8 Add `implements ShouldQueue` to `DevPingNotification`
- [ ] 2.9 Add `implements ShouldQueue` to `RefundStatusUpdated`
- [ ] 2.10 Add `implements ShouldQueue` to `AlternativeSourcingNotification`
- [ ] 2.11 Add `implements ShouldQueue` to `ClientAccountCreated`
- [ ] 2.12 Add `implements ShouldQueue` to `SourcingRequestCreated` (BaseAdminNotification child)
- [ ] 2.13 Add `implements ShouldQueue` to `ClientRegisteredForAdmins` (BaseAdminNotification child)
- [ ] 2.14 Add `implements ShouldQueue` to `QuotationAccepted` (BaseAdminNotification child)
- [ ] 2.15 Add `implements ShouldQueue` to `QuotationRejected` (BaseAdminNotification child)
- [ ] 2.16 Add `implements ShouldQueue` to `RefundRequestCreated` (BaseAdminNotification child)
- [ ] 2.17 Add `implements ShouldQueue` to `ProofOfPaymentUploaded` (BaseAdminNotification child)
- [ ] 2.18 Add `implements ShouldQueue` to `DossierAssignmentRemoved` (BaseAdminNotification child)
- [ ] 2.19 Add `implements ShouldQueue` to `SourcingRequestAssigned` (BaseAdminNotification child)
- [ ] 2.20 Add `implements ShouldQueue` to `AdminSourcingRequestStatusUpdated` (BaseAdminNotification child)
- [ ] 2.21 Add `implements ShouldQueue` to `QuotationNegotiationRequested` (BaseAdminNotification child)

## 3. Protect Synchronous Mail Calls

- [ ] 3.1 Wrap `CustomVerifyEmail` send in `app/Models/User.php:142` with try/catch, log to `mail` channel
- [ ] 3.2 Wrap unprotected `notify()` calls in `app/Http/Controllers/Admin/AdminSourcingRequestController.php:64`
- [ ] 3.3 Wrap unprotected `notify()` calls in `app/Http/Controllers/Admin/SourcingOrderController.php:268,538`
- [ ] 3.4 Wrap unprotected `notify()` calls in `app/Http/Controllers/Admin/QuotationController.php:277,502`
- [ ] 3.5 Wrap unprotected `notify()` calls in `app/Http/Controllers/Client/QuotationController.php:194,434`
- [ ] 3.6 Wrap unprotected `notify()` calls in `app/Http/Controllers/Client/SourcingOrderController.php:113`
- [ ] 3.7 Wrap unprotected `notify()` calls in `app/Http/Controllers/Client/RefundRequestController.php:148`
- [ ] 3.8 Wrap unprotected `notify()` calls in `app/Livewire/Admin/DevDashboard.php:1234,1662`
- [ ] 3.9 Wrap unprotected `notify()` calls in `app/Livewire/Admin/SourcingRequestCreate.php:149`
- [ ] 3.10 Wrap unprotected `notify()` calls in `app/Livewire/Admin/SourcingOrderWorkflow.php:120`
- [ ] 3.11 Wrap unprotected `notify()` calls in `app/Observers/SourcingRequestObserver.php:49,57`
- [ ] 3.12 Wrap unprotected `notify()` calls in `app/Listeners/NotifyAdminsOfClientRegistration.php:23`
