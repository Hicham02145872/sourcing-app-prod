## 1. Add email search to admin controllers that already search

- [x] 1.1 `AdminSourcingRequestController@index` — verify email search already works (via `user` relation), adjust wildcard to `%search%` if needed
- [x] 1.2 `SourcingOrderController@index` — verify email search already works (via `user` relation)
- [x] 1.3 `QuotationController@index` — verify email search already works (via `user` relation)
- [x] 1.4 `UserController@index` — change `LIKE '$search%'` to `LIKE '%$search%'` for both name and email

## 2. Add email search to admin controllers without it

- [x] 2.1 `RefundRequestController@index` — add search by `shared_id`, and via `user` relation (name, email) using `when($search)` with `->whereHas('user', ...)`

## 3. Add email search to client-side controllers

- [x] 3.1 `SourcingRequestController@handling` — add `orWhere('email', 'like', '%'.$search.'%')` via user relation
- [x] 3.2 `ClientDashboardController@index` — add `orWhere('email', 'like', '%'.$search.'%')` via user relation

## 4. Fix pagination search state loss

- [x] 4.1 `UserController@index` — add `->withQueryString()` to paginator
- [x] 4.2 `PaymentMethodController@index` — add `->withQueryString()` to paginator
- [x] 4.3 `CountryController@index` — add `->withQueryString()` to paginator
- [x] 4.4 `ServiceController@index` — add `->withQueryString()` to paginator
- [x] 4.5 `CategoryController@index` — add `->withQueryString()` to paginator
- [x] 4.6 `SourcingOrderController@index` — add `->withQueryString()` to paginator (was missing it)

## 5. Fix back links to preserve search state

- [x] 5.1 Replace hard-coded `route(...)` back links in `admin/sourcing-requests/show.blade.php` with `url()->previous()`
- [x] 5.2 Replace hard-coded `route(...)` back links in `admin/sourcing-orders/show.blade.php` with `url()->previous()`
- [x] 5.3 Replace hard-coded `route(...)` back links in `admin/quotations/show.blade.php` with `url()->previous()`
- [ ] ~~5.4 Replace hard-coded `route(...)` back links in `client/sourcing-requests/show.blade.php`~~ — No back links exist in this view (no breadcrumb)
- [ ] ~~5.5 Replace hard-coded `route(...)` back links in `client/sourcing-orders/show.blade.php`~~ — Back link is in breadcrumb passed to layout; can't replace directly

## 6. Verification

- [ ] 6.1 Verify admin sourcing requests list search by email returns correct results
- [ ] 6.2 Verify admin sourcing orders list search by email returns correct results
- [ ] 6.3 Verify client handling list search works
- [ ] 6.4 Verify pagination preserves search params on all fixed views
- [ ] 6.5 Verify back links return to list with search state intact
- [ ] 6.6 Verify refund request search works by shared_id and email
