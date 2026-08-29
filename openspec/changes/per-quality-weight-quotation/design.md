## Context

Quotations store prices per quality (`low` / `medium` / `good`) in a JSON column `quality_options` on the `quotations` table, but weight is currently a single order-level pair of columns (`unit_weight` decimal, `weight_unit` string `g`/`kg`/`colis`). The admin enters a distinct price per quality, yet can only enter one weight for the whole quotation. The requested change moves weight into each quality option so each quality carries its own weight and unit (g / kg / colis), and removes the order-level weight entirely.

Existing display surfaces (admin quotation show, admin/client sourcing request show, admin/client sourcing order show, shipping sheet) read `unit_weight` / `weight_unit`. Orders derive pricing from the selected quality's `quality_options` entry; weight must now follow the same pattern.

## Goals / Non-Goals

**Goals:**
- Add `weight` and `weight_unit` (g / kg / colis) to each quality entry inside `quality_options`
- Remove the order-level `unit_weight` / `weight_unit` columns and all form code that reads them
- Require a weight per quality on create and update with validation
- Display pages resolve the weight from the selected quality (fallback to a default quality when none is selected)
- Migrate existing data so no quotation loses its weight

**Non-Goals:**
- No structural change to how qualities are modeled (still a JSON column, no `quality` table)
- No change to pricing/amount calculation logic
- No change to the client selection mechanism (radio cards / buttons stay as-is); the weight is informational

## Decisions

### 1. Store weight inside the `quality_options` JSON (no new columns)

Each quality entry becomes:
```php
[
    'price'       => 12.5,
    'image_path'  => '...',
    'image_paths' => [...],
    'weight'      => 250.00,
    'weight_unit' => 'g',
]
```
- **Why**: Consistent with existing per-quality data (`price`, images). No schema change to store new data, no N+1, no new model.
- **Alternative considered**: A dedicated `quality_weights` table or pivot — rejected, overkill for 3 fixed quality keys stored in JSON.

### 2. Single migration: backfill then drop columns

One migration `2026_08_29_000000_move_weight_into_quality_options.php`:

1. For every quotation, read `unit_weight` / `weight_unit`; if `unit_weight` is not null and the `quality_options` entries exist, write `weight` / `weight_unit` into each present quality entry (default unit `g` when `weight_unit` is missing).
2. `update()` the JSON cast column in bulk (Eloquent handles the JSON mutation per row).
3. `Schema::table('quotations')` → `dropColumn(['unit_weight', 'weight_unit'])`.

- **Why**: single atomic step; backfill guarantees data safety before the drop.
- **Rollback**: `down()` recreates `unit_weight` (decimal 15,2) and `weight_unit` (string, default `g`) and copies the `medium` quality's weight back when present.

### 3. Form: weight inputs inside each quality block

- `partials/quality-options.blade.php`: inside each quality card, next to Unit Price, add:
  - `<input type="number" step="0.01" name="quality_options[{{ $key }}][weight]" ... value="{{ old('quality_options.'.$key.'.weight', $qualityWeightValues[$key] ?? '') }}">`
  - `<select name="quality_options[{{ $key }}][weight_unit]">` with `g`, `kg`, `colis`.
- `partials/logistics.blade.php`: remove the `unit_weight` / `weight_unit` block (lines 10–24).
- `create.blade.php`: pass `$qualityWeightValues => []` (and a units default array) to the partial.
- `edit.blade.php`: build `$qualityWeightValues` from `$quotation->quality_options` and pass to the partial.

### 4. Controller: validation + persistence

Validation (store and update), for each of `low`, `medium`, `good`:
```php
'quality_options.*.weight'      => 'required|numeric|min:0',
'quality_options.*.weight_unit' => 'required|string|in:g,kg,colis',
```
Remove the `unit_weight` / `weight_unit` rules and the fill on `Quotation::create` / `update`. In the `$qualityOptionsData[$quality]` build loop, also copy `weight` and `weight_unit` from the validated input.

### 5. Display: resolve weight from selected quality

Add a helper on `Quotation` (e.g. `weightForQuality(?string $quality)` or a computed accessor) that:
- if `selected_quality` is set, returns `quality_options[selected_quality]['weight']` / `['weight_unit']`
- else falls back to a default quality (`medium`) or returns `null`.

Replace `{{ $quotation->unit_weight }} {{ $quotation->weight_unit }}` in the display blade files:
- `admin/quotations/show.blade.php`
- `admin/sourcing-requests/show.blade.php`
- `client/sourcing-requests/show.blade.php`
- `admin/sourcing-orders/show.blade.php`
- `client/sourcing-orders/show.blade.php`
- `app/Services/ShippingCompanySheetService.php` (uses selected-quality weight in the sheet)

### 6. Client selection UI shows the weight per quality

The client sees each quality's weight directly in the selector:

- `client/sourcing-requests/show.blade.php` (quality cards, lines ~544–610): each card's `$opt` already contains the whole `quality_options[$key]` entry, so render a "Weight" line from `$opt['weight'] ?? '-'` and `$opt['weight_unit'] ?? 'g'`. Follow the active-card color logic (`text-white` on selected) so the weight text keeps contrast.
- Dynamic Unit Weight tile (lines ~349–352): add a `qualityWeights` map to the Alpine `x-data` (e.g. `{ low: ['250','g'], medium: ['2','kg'], good: [...] }`) and extend the existing `$watch('selectedQuality')` to update a `#dynamic-unit-weight` element, mirroring `#dynamic-unit-price`.
- `client/quotations/index.blade.php` (quality buttons, lines ~299–313): append the weight (`250 g`, `2 kg`, …) to each "Select Quality" button label so the client sees it before accepting.

- **Why**: the grammage informs the client's quality choice; because it is per-quality data, it must live with the option (as with price), not in a shared doc-level field.
- **Alternative considered**: showing weight only after acceptance — rejected, the client needs it at decision time.

## Risks / Trade-offs

- Existing quotations with `unit_weight = null` lose nothing, but their quality options get no weight → display shows `-`. **Mitigation**: default fallback quality and a clear dash in views.
- Some order-level weight consumers might be missed (grep for `unit_weight` / `weight_unit` during implementation). **Mitigation**: exhaustive grep before/after; all reads routed through the model helper.
- Removing a required global field while making weight required per-quality temporarily worsens UX for admins who only ever entered one weight. **Mitigation**: the edit form pre-fills per-quality weights from stored JSON, so existing quotations still show all three weights.
- JSON backfill in migration must be careful with the `array` cast (decode, mutate, re-encode). **Mitigation**: use Eloquent on `Quotation` with lazy-loaded `quality_options`, then `save()`.