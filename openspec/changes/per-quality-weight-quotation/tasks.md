## 1. Data Model

- [x] 1.1 Update `app/Models/Quotation.php`: remove `unit_weight` and `weight_unit` from `$fillable` and `$casts`
- [x] 1.2 Add a `weightForQuality(string $quality = null)` helper (or computed accessor) on `Quotation` that returns `['weight' => ..., 'weight_unit' => ...]` from `quality_options`, honoring `selected_quality` with fallback to `medium` and `null` when absent

## 2. Migration

- [x] 2.1 Create migration `database/migrations/2026_08_29_000000_move_weight_into_quality_options_table.php` that backfills each existing quality entry's `weight` / `weight_unit` from `unit_weight` / `weight_unit` (default unit `g`)
- [x] 2.2 In the same migration, drop the `unit_weight` and `weight_unit` columns from `quotations`
- [x] 2.3 Implement `down()` re-adding both columns and restoring `medium` quality weight when present
- [x] 2.4 Run `php artisan migrate` and confirm existing rows keep their weight

## 3. Quotation Form

- [x] 3.1 In `resources/views/admin/quotations/partials/quality-options.blade.php`, add a `weight` number input and a `weight_unit` select (g / kg / colis) inside each quality card, named `quality_options[<key>][weight]` / `quality_options[<key>][weight_unit]`, defaulting to `$qualityWeightValues[$key]` / `$qualityWeightUnits[$key]`
- [x] 3.2 Remove the `unit_weight` / `weight_unit` block from `resources/views/admin/quotations/partials/logistics.blade.php`
- [x] 3.3 In `resources/views/admin/quotations/create.blade.php`, pass empty `$qualityWeightValues` / `$qualityWeightUnits` to the quality-options include
- [x] 3.4 In `resources/views/admin/quotations/edit.blade.php`, build `$qualityWeightValues` / `$qualityWeightUnits` from `$quotation->quality_options` and pass them to the partial

## 4. Controller

- [x] 4.1 In `app/Http/Controllers/Admin/QuotationController.php` `store()`: remove `unit_weight` / `weight_unit` validation rules and add `quality_options.*.weight` (required|numeric|min:0) and `quality_options.*.weight_unit` (required|in:g,kg,colis) rules
- [x] 4.2 In `store()`: copy `weight` / `weight_unit` into each `$qualityOptionsData[$quality]` entry and stop writing `unit_weight` / `weight_unit` to the quotation
- [x] 4.3 In `update()`: apply the same validation changes
- [x] 4.4 In `update()`: merge `weight` / `weight_unit` into each updated quality option and stop writing `unit_weight` / `weight_unit`

## 5. Display Surfaces

- [x] 5.1 Replace global weight display with `weightForQuality()` on `resources/views/admin/quotations/show.blade.php`
- [x] 5.2 Replace global weight display on `resources/views/admin/sourcing-requests/show.blade.php`
- [x] 5.3 Replace global weight display on `resources/views/client/sourcing-requests/show.blade.php`
- [x] 5.4 Replace global weight display on `resources/views/admin/sourcing-orders/show.blade.php`
- [x] 5.5 Replace global weight display on `resources/views/client/sourcing-orders/show.blade.php`
- [x] 5.6 Update `app/Services/ShippingCompanySheetService.php` to use the selected quality's weight
- [x] 5.7 Grep for remaining `unit_weight` / `weight_unit` references and update or remove them (including any partials/controllers not listed)
- [x] 5.8 In `resources/views/client/sourcing-requests/show.blade.php`, render a "Weight" line on each quality card from `$opt['weight'] ?? '-'` and `$opt['weight_unit'] ?? 'g'` (respecting active-card color contrast)
- [x] 5.9 In `resources/views/client/sourcing-requests/show.blade.php`, add a `qualityWeights` map to the Alpine `x-data` and extend `$watch('selectedQuality')` to update a `#dynamic-unit-weight` tile (replacing the static global Unit Weight tile)
- [x] 5.10 In `resources/views/client/quotations/index.blade.php`, append each quality's weight (`250 g`, `2 kg`, …) to the "Select Quality" button labels

## 6. Verification

- [x] 6.1 Run `php artisan migrate` / `migrate:status` and inspect migrated plausibility
- [x] 6.2 Run `php artisan test` (or project test suite) and confirm no regressions
- [x] 6.3 Manually verify create/edit quotation now requires per-quality weight in g/kg/colis and displays selected-quality weight on show pages
- [x] 6.4 Manually verify the client quality selector shows each quality's weight and that the weight updates when the client picks a different quality