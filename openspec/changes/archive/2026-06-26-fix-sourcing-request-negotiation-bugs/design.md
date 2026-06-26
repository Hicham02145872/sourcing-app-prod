## Context

Two production errors affect the sourcing request pages:

1. **`Call to a member function format() on string`** — The `sourcing_requests` table has `negotiated_at` and `accepted_at` timestamp columns, but `SourcingRequest` model lacks `$casts` for them. When the model is freshly created (e.g., via `transitionTo('negotiating')`), `$this->negotiated_at = now()` stores a Carbon instance in memory. After the model is re-retrieved from the database, the raw datetime string is returned. Calling `->format()` on a string throws an Error. Affected views: `admin/sourcing-requests/show.blade.php` (line 90) and `client/sourcing-requests/show.blade.php` (5 occurrences).

2. **`View [client.sourcing-requests.index] not found`** — The controller returns `view('client.sourcing-requests.index')` but no `index.blade.php` exists in `resources/views/client/sourcing-requests/`. Other views for the same controller exist (`create`, `show`, `edit`, `archived`, `handling`).

## Goals / Non-Goals

**Goals:**
- Fix the 500 error on `admin/sourcing-requests/show` for negotiating/accepted status
- Fix the 500 error on `client/sourcing-requests` index page
- Add missing `datetime` casts to `SourcingRequest` model
- Create the missing client index view
- Prevent recurrence with safer date formatting patterns

**Non-Goals:**
- Not refactoring the entire view layer
- Not changing the Cloudinary integration (separate change)
- Not changing the database schema

## Decisions

| Decision | Rationale |
|---|---|
| **Add `negotiated_at` and `accepted_at` to `$casts` as `'datetime'`** | Simplest fix with no schema changes. Uses Laravel's built-in casting. Both columns are nullable timestamps — exactly what `'datetime'` cast expects. |
| **Redirect `index` to `handling` route** | The `index` view file was never created and the `handling` view already provides the same listing functionality with search/filter. Redirecting avoids duplicating code. |
| **Use `?->format(...)` (null-safe operator) in views** | Provides defense-in-depth. If a field is null for any reason (e.g., status transition didn't set it), it won't crash. Only applies where the check is `$obj->field ? $obj->field->format(...)` — can be simplified to `$obj->field?->format(...)`. |
| **No custom `safeFormat()` helper** | Over-engineering for two date fields. The cast + null-safe operator is sufficient and idiomatic Laravel. |

## Risks / Trade-offs

- **[Regression]** Adding casts changes the type returned from DB queries. If any code serializes the model to JSON (`->toJson()` or API responses), `negotiated_at`/`accepted_at` will now appear as ISO 8601 strings instead of raw DB strings. → **Mitigation**: This is actually the desired behavior and matches how `assigned_at`, `created_at`, `updated_at` already work.
- **[Missing data]** If rows exist where `negotiated_at` or `accepted_at` contain non-timestamp strings (e.g., due to a previous bug), the cast may fail. → **Mitigation**: Check production data before deploying; if corrupt values exist, fix them inline.
