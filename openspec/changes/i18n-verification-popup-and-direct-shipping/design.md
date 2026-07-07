## Context

The codebase currently has two issues:

1. **Product-warning popup i18n**: In `resources/views/components/product-warning-popup.blade.php`, all `__()` helper calls use French phrases as keys (e.g., `__("J'ai compris")`, `__("Avertissement concernant les produits proposés")`). These keys don't match entries in `lang/en.json` or `lang/ar.json`, making the popup untranslatable for non-French users. The standard convention used elsewhere in the app is English-as-key.

2. **Direct/Indirect shipping**: While the codebase has logic to differentiate "direct" (China→destination) vs "indirect" (China→Dubai→destination) shipping, this distinction is currently based on item naming conventions (`isUaeHubItemStyle` heuristic). There is no explicit per-country flag, so every country always shows both direct and indirect options in the routing popup and admin edit pages. Some countries should only show direct shipping (no Dubai hub routing).

## Goals / Non-Goals

**Goals:**
- Make the product-warning popup fully translatable (FR, EN, AR)
- Add a `is_direct` boolean column to the `countries` table
- Allow admins to toggle the direct-shipping flag per country
- Conditionally hide indirect shipping UI elements based on the flag

**Non-Goals:**
- Not retroactively migrating existing countries' is_direct status (default false)
- Not changing the shipping fee calculation logic itself, only the UI visibility
- Not adding i18n to the verification-popup (the countdown popup) - only the product-warning popup

## Decisions

### Decision 1: English keys for product-warning popup

**Choice**: Replace all French-keyed `__()` calls with English keys and add translations to all three locale files.

**Rationale**: This matches the existing convention used throughout the rest of the codebase (e.g., `{{ __('Dashboard') }}`, `{{ __('New Request') }}`). The Laravel `__()` helper falls back to the key itself if no translation is found, so English keys serve as both the key and the English translation.

**Alternatives considered**: Adding the French phrases as keys in en.json would work but would be inconsistent with the rest of the codebase.

### Decision 2: `is_direct` as a boolean column on `countries`

**Choice**: Add a simple `is_direct` boolean (default false) to the `countries` table.

**Rationale**: The direct/indirect nature is a property of the destination country, not of individual shipping fee items. A boolean on the country model is the simplest approach. The existing heuristic (`isUaeHubItemStyle`) can remain as a safety net for backward compatibility, but the explicit flag takes precedence.

**Alternatives considered**: 
- Deriving it from shipping fee items data: unreliable, complex querying
- Feature flag per country: overkill for a binary property

### Decision 3: Toggle placement in admin shipping fee edit page

**Choice**: Add the toggle in the "Location Overview" card of `shipping-fee-edit.blade.php`, near the currency selector and transport units section.

**Rationale**: This keeps all country-level shipping configuration in one place. The edit page already has sections for per-country settings. Adding it here avoids creating a new admin page.

### Decision 4: Conditional rendering in blade/Livewire via `is_direct` property

**Choice**: Pass `$country->is_direct` to the views and use `@if` / `x-show` to conditionally show/hide indirect sections.

**Rationale**: Simplest approach. The indirect sections are purely UI elements (columns, cards, popup options). No backend logic changes are needed except in `getRatesForPopup` which should return empty indirect data for direct countries.

## Risks / Trade-offs

- **[Risk]** Existing countries with no explicit flag will default to `is_direct = false` (indirect), showing both options as before. No migration issue → **Mitigation**: This is intentional; existing behavior is preserved.
- **[Risk]** The `isUaeHubItemStyle` heuristic may conflict with the explicit flag → **Mitigation**: The explicit `is_direct` flag takes precedence. The heuristic serves as fallback only when flag is absent (shouldn't happen after migration).
- **[Risk]** Hiding the indirect column in admin edit may confuse admins who have already entered indirect prices → **Mitigation**: When toggling to direct, indirect price data is still preserved in the database (not deleted), just hidden from the UI. Toggling back restores visibility.
