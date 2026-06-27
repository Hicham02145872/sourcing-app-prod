## Context

The app has ~15 list/index views across Admin and Client namespaces with inconsistent search behavior. Some search by email, some don't. Pagination state is lost on several views. Show/detail views have hard-coded "back" links that lose the search context. No existing specs exist.

## Goals / Non-Goals

**Goals:**
- Every admin list with a search bar also searches by client email
- Client-side lists (handling, dashboard, orders) add email to their search scope
- Pagination preserves search parameters on ALL list views (using `->withQueryString()`)
- "Back" links on show/detail views return to the previous list with search/filter state intact
- Refund requests get basic text search (shared_id, user name/email)

**Non-Goals:**
- Not redesigning the search UI or adding new filter types (date ranges, etc.)
- Not adding search to pages that don't already have a search bar or obvious need (ShippingCompany, ShipmentCalendar, TrackingLog)
- Not adding server-side session storage of search state — URL query params are sufficient

## Decisions

1. **Use `url()->previous()` for back links** — Simple, no session storage needed. Returns to the exact previous URL including query params. Replace `route(...)` hard-coded back links in show views with `<a href="{{ url()->previous() }}">`.

2. **Use `->withQueryString()` on all paginators** — Eliminates the need for manual `->appends()` in blade views. Already used by some controllers; will add to the rest (UserController, PaymentMethodController, CountryController, ServiceController, CategoryController, client orders).

3. **Add `$request->search` to WHERE clauses** — For controllers that already search, simply add `orWhere('email', 'like', '%'.$search.'%')` on the user relation where missing. For controllers without search, add a `when($search)` block.

4. **Fix `UserController` wildcard** — Change from prefix match (`$search.'%'`) to contains match (`'%'.$search.'%'`) for consistency with all other controllers.

5. **Refund requests** — Add search by `shared_id` and via user relation (name, email) using the same pattern as other controllers.

## Risks / Trade-offs

- `url()->previous()` depends on the HTTP Referer header — reliable for standard navigation but may not work if user opens in a new tab or a proxy strips the header. Fallback: keep a default route as second argument.
- Adding email search widens the result set — risk negligible since search terms are user-entered and already broad (contains match). MySQL performance on `LIKE '%...%'` is adequate for the data volume.
