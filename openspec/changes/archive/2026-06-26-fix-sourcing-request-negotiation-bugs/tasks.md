## 1. Fix `format()` on string error (casts + views)

- [x] 1.1 Add `'negotiated_at' => 'datetime'` and `'accepted_at' => 'datetime'` to `$casts` in `app/Models/SourcingRequest.php`
- [x] 1.2 Update `resources/views/admin/sourcing-requests/show.blade.php` line 90: use `?->format()` instead of ternary + `->format()`
- [x] 1.3 Update `resources/views/client/sourcing-requests/show.blade.php`: fix all 5 `->format()` calls on `negotiated_at`/`accepted_at` to use `?->format()` or null-safe pattern
- [x] 1.4 Run `php artisan view:clear` to clear compiled views
- [x] 1.5 Verify admin show page loads for sourcing requests in negotiating/accepted/pending status

## 2. Fix missing client index view

- [x] 2.1 Redirect `index` route to `handling` in `SourcingRequestController` instead of missing view
- [x] 2.2 Verify `GET /{locale}/client/sourcing-requests` redirects to handling instead of 500

## 3. Verify and clean up

- [x] 3.1 Check production data for any corrupt `negotiated_at`/`accepted_at` values that could break datetime casting
- [x] 3.2 Run `php artisan cache:clear` on production after deploy
