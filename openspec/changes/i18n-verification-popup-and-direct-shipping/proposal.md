## Why

The product-warning popup (fake product verification) displayed when creating a new sourcing request contains hardcoded French text used as translation keys, making it untranslatable for English and Arabic users. Additionally, the shipping fees system lacks a per-country "direct vs indirect" flag, forcing all countries to show both direct (China→destination) and indirect (via Dubai) shipping options regardless of actual logistics routing.

## What Changes

1. **Product-warning popup i18n (Change 1)**: Replace hardcoded French-keyed strings with proper English translation keys in `product-warning-popup.blade.php`. Add the corresponding translations to `lang/fr.json`, `lang/en.json`, and `lang/ar.json`.

2. **Direct/Indirect shipping toggle per country (Change 2)**: 
   - Add a `is_direct` boolean column to the `countries` table
   - Add a toggle switch in the admin country edit form (or shipping fees edit page) to mark a country as "direct" (China→destination, no Boai/Dubai hub)
   - When a country is `is_direct = true`:
     - In the admin shipping fee edit page: hide the "Indirect (Dubai)" column (price_per_kg_china_to_dubai, price_per_kg_dubai_to_africa)
     - In the client shipping routing popup: only show the "Direct Shipping" option, hide "Indirect Shipping"
     - In the client shipping fees list page: hide Dubai-related pricing columns for transport modes
   - When `is_direct = false`: behavior remains as-is (show both direct and indirect options)

## Capabilities

### New Capabilities
- `product-warning-i18n`: Translation support for the product warning/fake product verification popup
- `direct-shipping-flag`: Per-country direct/indirect shipping toggle with conditional UI rendering

### Modified Capabilities
- (none)

## Impact

- **Files to modify**: `resources/views/components/product-warning-popup.blade.php`, `lang/fr.json`, `lang/en.json`, `lang/ar.json`, `app/Models/Country.php`, admin shipping fee Livewire component/view, client shipping fee Livewire component/view, client create request view (routing popup), `app/Http/Controllers/Client/ShippingFeeController.php`
- **Database**: New migration to add `is_direct` column to `countries` table
- **No breaking API changes**
