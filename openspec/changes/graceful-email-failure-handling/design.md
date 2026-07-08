## Context

The app sends emails through notifications (24+ notification classes with `mail` channel) and direct `Mail::send/to` calls. Almost all notifications use the `Queueable` trait but **do not** implement the `ShouldQueue` interface, meaning `$user->notify()` sends mail synchronously during the HTTP request. A mail transport failure (SMTP timeout, auth error) propagates as an unhandled exception → 500 error page.

16 out of ~30 email sending locations have no try/catch protection. The global exception handler has no mail-specific transport handling.

## Goals / Non-Goals

**Goals:**
- Make every notification that sends via the `mail` channel implement `ShouldQueue` so mail failures never block HTTP responses
- Wrap every unprotected synchronous `Mail::send/to` call in try/catch with logging
- Log all mail failures to a dedicated `mail` log channel for operational visibility

**Non-Goals:**
- No changes to mail configuration, transport setup, or queue infrastructure
- No changes to notification content, recipients, or timing
- No changes to FCM or database notification channels
- No retry logic beyond what Laravel's queue system provides by default

## Decisions

1. **Queue notifications (ShouldQueue) over wrapping every notify() call in try/catch**
   - *Alternatives considered*: Wrapping each `notify()` individually, creating a custom mail channel with error handling
   - *Rationale*: `ShouldQueue` is the idiomatic Laravel approach — mail is already deferred, the queue worker handles retries, and the HTTP response is never affected. All notifications already use `Queueable` + `afterCommit()`, so they just need the interface. This is a one-line change per class vs. adding try/catch at every call site.
   - *Note*: Notifications that explicitly need synchronous delivery (e.g., `CustomVerifyEmail`) will not be queued — they get a try/catch instead.

2. **Dedicated `mail` log channel for silent failures**
   - *Rationale*: Standardized logging makes monitoring easy. Log with context (recipient, notification type, error message) at `error` level so ops tools can alert.

3. **No changes to BaseAdminNotification or FilterAdminNotificationMailChannel**
   - *Rationale*: These already gate mail delivery but don't handle transport errors. Once notifications are queued, transport errors happen in the queue worker and are logged there.

## Risks / Trade-offs

- **Queue worker required**: If notifications are queued, a queue worker must run (`php artisan queue:work`). The app already uses `database` queue connection and `ProformaInvoiceMail` already queues, so infrastructure exists. Risk is low.
- **Delayed delivery**: Queued mail may take a few seconds longer to send. Acceptable for non-critical notifications.
- **CustomVerifyEmail cannot be queued**: Laravel's `VerifyEmail` notification has specific timing requirements. It will remain synchronous but wrapped in try/catch.
