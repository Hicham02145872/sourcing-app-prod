## Why

The admin shipping fees edit page supports currency selection per country, but the index table doesn't display which currency each country uses, and the Excel import always defaults to USD. Additionally, prices in the shipping fee edit page should display currency symbols consistently like in the quotation creation page.

## What Changes

- Add currency column to the admin shipping fees index table showing each country's configured currency
- Ensure the Excel import respects the selected currency instead of hardcoding USD
- Use the same 9-currency hardcoded list from quotations for consistency across admin pages (instead of the full 164 config list)
- Display currency symbols alongside prices throughout the shipping fees edit page matching the quotation page's pattern

## Capabilities

### New Capabilities
- `shipping-fees-currency`: Add currency support to the admin shipping fees index and Excel import

### Modified Capabilities
- *(none)*

## Impact

- `app/Livewire/Admin/ShippingFeesTable.php` — add currency display to table
- `app/Livewire/Admin/ShippingFeeEdit.php` — align currency list to match quotations (9 currencies), improve symbol display
- `resources/views/livewire/admin/shipping-fees-table.blade.php` — add currency column
- `resources/views/livewire/admin/shipping-fee-edit.blade.php` — add currency symbols to price rows
- `app/Imports/ShippingFeesFromAirFreightDDPImport.php` — use dynamic currency instead of hardcoded USD
- No database changes required (currency column already exists on shipping_fees)
