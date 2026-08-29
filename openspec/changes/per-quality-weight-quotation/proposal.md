## Why

Today the weight is a single global field on the quotation (`unit_weight` + `weight_unit`, with units g / kg / colis), while prices are already captured per quality (low / medium / good) inside the `quality_options` JSON. There is no way to express a different weight per quality, and the current design associates the weight with the whole order instead of with each quality. The user wants the weight expressed in grams/kg and attached to EACH quality, not to the general order.

## What Changes

- Add a weight input and unit selector (g, kg, colis) inside each quality block in the quotation create/edit form: `quality_options[<quality>][weight]` and `quality_options[<quality>][weight_unit]`
- Store weight data inside the `quality_options` JSON column for each quality (`low`, `medium`, `good`)
- **BREAKING**: Remove the quotation-level weight fields `unit_weight` and `weight_unit` (columns dropped via migration)
- Backfill existing quotations: carry the current global weight over into each quality option (or into the selected quality) before dropping the columns
- Update every display page (admin/client quotation, sourcing request, sourcing order) so the weight shown comes from the selected quality's option instead of the order-level column
- Client quality selection pages show the weight / grammage of each quality option in the selector (cards on `client/sourcing-requests/show`, quality buttons on `client/quotations/index`), and the displayed weight updates dynamically when the client changes the selected quality
- Update validation rules: per-quality weight is required, numeric, and its unit is one of `g`, `kg`, `colis`

## Capabilities

### New Capabilities
- `quotation-per-quality-weight`: Quotations capture, store and display a weight (with g/kg/colis unit) for each quality option instead of a single order-level weight

### Modified Capabilities

## Impact

- `app/Http/Controllers/Admin/QuotationController.php`: store() and update() — validation, parsing and persisting per-quality weight
- `app/Models/Quotation.php`: fillable + casts — remove `unit_weight`/`weight_unit`, keep `quality_options` as array
- `resources/views/admin/quotations/create.blade.php`: pass per-quality weight defaults to the quality-options partial
- `resources/views/admin/quotations/edit.blade.php`: load existing per-quality weights into the partial
- `resources/views/admin/quotations/partials/quality-options.blade.php`: add weight + unit inputs per quality
- `resources/views/admin/quotations/partials/logistics.blade.php`: remove the global unit weight field
- Display views: `admin/quotations/show.blade.php`, `admin/sourcing-requests/show.blade.php`, `client/sourcing-requests/show.blade.php`, `admin/sourcing-orders/show.blade.php`, `client/sourcing-orders/show.blade.php`
- Client quality selection UI: `resources/views/client/sourcing-requests/show.blade.php` (quality cards + dynamic weight tile), `resources/views/client/quotations/index.blade.php` (quality buttons)
- Database migration: backfill and drop `unit_weight` / `weight_unit` columns on `quotations`
- `app/Services/ShippingCompanySheetService.php`: use the selected quality's weight