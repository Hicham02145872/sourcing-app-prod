## Why

Two production 500 errors are blocking users from accessing sourcing request pages: (1) `Call to a member function format() on string` when viewing a sourcing request with `negotiated_at`/`accepted_at` timestamps in the admin show view (status: negotiating/accepted), and (2) `View [client.sourcing-requests.index] not found` when clients visit their sourcing requests list. Both are critical regressions that need immediate fixes.

## What Changes

- **Fix `format()` on string error**: Add missing `datetime` casts for `negotiated_at` and `accepted_at` columns in `SourcingRequest` model so they return Carbon instances instead of raw strings
- **Fix missing client index view**: Create the missing `resources/views/client/sourcing-requests/index.blade.php` view file so the client sourcing requests list page renders correctly
- **Add safe-guard format helper**: Optionally add a `safeFormat()` helper or null-safe accessor pattern to prevent similar issues in the future

## Capabilities

### New Capabilities
- `date-format-robustness`: Centralized safe date formatting to prevent `format()` on string/null errors across all Blade views

### Modified Capabilities
- (none — fixes are bug fixes, not spec-level requirement changes)

## Impact

- **Affected code**:
  - `app/Models/SourcingRequest.php` — add casts
  - `resources/views/admin/sourcing-requests/show.blade.php` — verify/clean up format calls
  - `resources/views/client/sourcing-requests/show.blade.php` — verify/clean up format calls
  - `resources/views/client/sourcing-requests/index.blade.php` — create missing file
- **Dependencies**: None
- **Risk**: Low — adding casts changes the type returned from DB, but `negotiated_at` and `accepted_at` are only used in views for display
