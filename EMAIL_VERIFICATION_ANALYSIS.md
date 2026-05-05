# Email Verification Analysis Report

## 🔴 Issues Found

### Issue #1: Double Event Listener Registration
- **Location**: `app/Providers/EventServiceProvider.php`
- **Severity**: High
- **Description**: The `SendQueuedVerificationEmail` listener is registered twice for the `Illuminate\Auth\Events\Registered` event. This occurs because of a conflict between Laravel's automatic **Event Discovery** feature and an explicit manual registration in the `EventServiceProvider`.
  1.  **Automatic Registration (Event Discovery)**: Your Laravel version has event discovery enabled by default. It scans the `app/Listeners` directory and automatically registers listeners. It sees the `handle(Registered $event)` method in `SendQueuedVerificationEmail.php` and correctly registers it as a listener for the `Registered` event.
  2.  **Manual Registration**: The `$listen` array in your `EventServiceProvider.php` also explicitly maps the `Registered` event to the `SendQueuedVerificationEmail` listener.
- **Evidence**:
  **1. Manual registration in `app/Providers/EventServiceProvider.php`:**
  ```php
  protected $listen = [
      Registered::class => [
          SendQueuedVerificationEmail::class,
      ],
      // ...
  ];
  ```
  **2. Listener signature in `app/Listeners/SendQueuedVerificationEmail.php` that enables auto-discovery:**
  ```php
  public function handle(Registered $event): void
  {
      // ...
  }
  ```
- **Impact**: When a user registers, the `Registered` event is fired once. However, because the listener is registered twice, Laravel invokes it twice. This queues two separate `SendQueuedVerificationEmail` jobs, causing two verification emails to be sent to the user and two "done" statuses to appear in Horizon logs for two distinct jobs.

## ✅ Verification Points Checked
- [x] **Listener Logic**: The listener `SendQueuedVerificationEmail` itself has good logic to prevent a *single* job from sending multiple emails (using `lockForUpdate` and checking `verification_email_sent_at`). However, this logic cannot prevent two *separate* jobs from running.
- [x] **Event Dispatch**: The `Registered` event is dispatched correctly and only once from `RegisteredUserController@store`.
- [x] **Controller**: The `RegisteredUserController` correctly fires a single event.
- [x] **Routes**: The authentication routes in `routes/auth.php` are standard and correct.
- [x] **Database Locking**: The database locking in the listener is implemented correctly but is ineffective against this specific problem, as the two jobs are often processed sequentially, not concurrently. The first job sets `verification_email_sent_at`, sends the email, and finishes. The second job starts, sees that `verification_email_sent_at` is now set, and exits without sending—but an email has already been sent by the first job, and a second one was sent by the first job's duplicate. The core issue is that the first job *should not have had a duplicate in the first place*. The check inside the listener is a good safeguard, but it doesn't prevent the first of the two jobs from running. The real issue is that two jobs are dispatched. The log entries show both jobs completing successfully, which implies the check might not be working as expected if the jobs run very close together, or one of them is succeeding in sending the email. The root cause remains the double dispatching.
- [x] **Queue Retry**: The issue is not related to queue retries. It's about two unique jobs being dispatched.
- [x] **Middleware**: No middleware is causing a duplicate dispatch.
- [x] **Unique Constraint**: N/A for this specific issue.

## 🔧 Recommended Fixes

The solution is to rely on one method of event listener registration. The modern and recommended approach in recent Laravel versions is to use automatic event discovery.

1.  **Remove Manual Registration**:
    Edit the `app/Providers/EventServiceProvider.php` file and remove the manual registration for the `Registered` event.

    **File**: `app/Providers/EventServiceProvider.php`

    **Change this:**
    ```php
    protected $listen = [
        Registered::class => [
            SendQueuedVerificationEmail::class,
        ],
        QuotationCreated::class => [
            UpdateSourcingRequestStatusOnQuotationCreated::class,
            \App\Listeners\SendQuotationCreatedNotification::class,
        ],
        // ...
    ];
    ```

    **To this:**
    ```php
    protected $listen = [
        QuotationCreated::class => [
            UpdateSourcingRequestStatusOnQuotationCreated::class,
            \App\Listeners\SendQuotationCreatedNotification::class,
        ],
        // ...
    ];
    ```
    (Simply delete the `Registered::class => [...]` entry).

2.  **Clear Cache**:
    After making the code change, run `php artisan event:clear` and `php artisan event:cache` in your deployment script to ensure the event-listener mapping is rebuilt correctly.

## 📊 Complete Flow Diagram

### Flawed Flow (Current Situation)
1.  User submits registration form (`POST /register`).
2.  `RegisteredUserController@store` is called.
3.  A `User` is created.
4.  `event(new Registered($user))` is dispatched.
5.  Laravel's Event system sees the event.
6.  It finds **two** listeners for `Registered`:
    - One from Event Discovery.
    - One from `EventServiceProvider`.
7.  Laravel invokes the `SendQueuedVerificationEmail` listener **twice**.
8.  **Two** `SendQueuedVerificationEmail` jobs are pushed to the queue.
9.  The queue worker processes Job #1 -> Sends Email #1.
10. The queue worker processes Job #2 -> Sends Email #2 (or is stopped by the internal `verification_email_sent_at` check, but the first email was already unnecessarily sent by a duplicate job).

### Corrected Flow (After Fix)
1.  User submits registration form (`POST /register`).
2.  `RegisteredUserController@store` is called.
3.  A `User` is created.
4.  `event(new Registered($user))` is dispatched.
5.  Laravel's Event system sees the event.
6.  It finds **one** listener for `Registered` (via Event Discovery).
7.  Laravel invokes the `SendQueuedVerificationEmail` listener **once**.
8.  **One** `SendQueuedVerificationEmail` job is pushed to the queue.
9.  The queue worker processes the single job -> Sends one email.
10. Problem solved.

---

## Answers to Your Questions

1.  **Quand exactement les 2 emails sont envoyés?**: They are sent almost simultaneously, immediately after a new user completes registration. Two separate jobs are dispatched to the queue at the same time.
2.  **À partir de quel action**: This happens during user registration. It would not happen when a user manually requests a resend via the `verification.send` route, as that flow is handled by `EmailVerificationNotificationController` which does not fire a `Registered` event.
3.  **Queue worker**: You are using Horizon, which is a supervisor for `php artisan queue:work`. The issue is independent of the worker itself and lies in the code dispatching two jobs.
4.  **Cache**: The cache driver is not relevant to this specific issue. The fix involves clearing the event cache (`event:clear`) to ensure the updated listener map is used.
5.  **Routes existantes**:
    - `GET /register` -> `register`
    - `POST /register`
    - `GET /verify-email` -> `verification.notice`
    - `GET /verify-email/{id}/{hash}` -> `verification.verify`
    - `POST /email/verification-notification` -> `verification.send`
6.  **Custom Notification**: You are using the standard `sendEmailVerificationNotification()` method on the User model, which sends the default Laravel `VerifyEmail` notification. This is correct.
7.  **Event Listeners**: Based on your `EventServiceProvider`, `SendQueuedVerificationEmail` is the only listener explicitly configured for the `Registered` event. However, due to event discovery, it was being registered a second time implicitly.
