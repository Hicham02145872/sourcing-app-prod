## Why

If SMTP or any mail transport fails (connection timeout, authentication error, etc.), the app shows a 500 error page to the user because most email sending is synchronous and unprotected. The user's action (placing an order, uploading proof, etc.) should succeed even if the email notification fails — the failure should be logged silently.

## What Changes

- Queue all notifications that send via the `mail` channel so mail failures never block HTTP responses
- Add try/catch around all synchronous `Mail::send/to/queue` calls that lack protection
- Create a reusable `SilentFailMail` trait or helper to standardize the pattern across direct mail calls
- Log all mail failures with a dedicated `mail` log channel for monitoring

## Capabilities

### New Capabilities
- `queue-all-mail-notifications`: Ensure every notification that uses the `mail` channel implements `ShouldQueue` so email failures are deferred to the queue worker
- `protect-synchronous-mail-calls`: Wrap all unprotected synchronous `Mail::send/to` calls in try/catch with logging

### Modified Capabilities
- (none)

## Impact

- `app/Notifications/*.php`: Add `ShouldQueue` interface to every notification that has `mail` in `via()` and already uses `Queueable` trait
- `app/Models/User.php`: Wrap `CustomVerifyEmail` send in try/catch
- `app/Http/Controllers/Admin/*.php`: Wrap unprotected `notify()` calls in try/catch
- `app/Http/Controllers/Client/*.php`: Wrap unprotected `notify()` calls in try/catch
- `app/Listeners/*.php`: Wrap unprotected `notify()` calls in try/catch (already mostly covered)
- `app/Observers/*.php`: Wrap unprotected `notify()` calls in try/catch
- `app/Livewire/Admin/*.php`: Wrap unprotected `notify()` calls in try/catch
- No new dependencies, no new routes, no database changes
