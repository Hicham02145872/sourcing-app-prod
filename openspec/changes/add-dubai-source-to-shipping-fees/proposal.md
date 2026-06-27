## Why

The admin shipping fees page has no Dubai/UAE hub pricing fields, even though the client side already distinguishes direct (China) and indirect (Dubai) shipping rates. Admins cannot set separate Dubai prices per transport type and item style, forcing them to use workarounds or leave Dubai pricing unconfigured.

## What Changes

- Add a `price_per_kg_dubai` column to `shipping_fee_items` table (nullable decimal)
- In each transport tab (air/sea/train), add a second price column "Indirect (Dubai)" next to the existing "Direct (China)" column
- The `ShippingFeeEdit` Livewire component and its view will read/write both price columns
- The client-side `ShippingFeesList` and `ShippingFeeController@getRatesForPopup` will use the Dubai price when displaying UAE hub items
- No **BREAKING** changes — existing data is preserved

## Capabilities

### New Capabilities
- `shipping-fees-dubai-source`: Add Dubai/UAE hub pricing to admin shipping fees with direct/indirect price columns per transport type

### Modified Capabilities
- *(none)*

## Impact

- `database/migrations/####_##_##_######_add_price_per_kg_dubai_to_shipping_fee_items_table.php` — new migration
- `app/Livewire/Admin/ShippingFeeEdit.php` — handle `price_per_kg_dubai` in itemsData, save, mount, init
- `resources/views/livewire/admin/shipping-fee-edit.blade.php` — add "Indirect (Dubai)" price column in each tab table
- `app/Livewire/Client/ShippingFeesList.php` — read `price_per_kg_dubai` for UAE hub items
- `app/Http/Controllers/Client/ShippingFeeController.php` — return `price_per_kg_dubai` in getRatesForPopup / getShippingFee
- `resources/views/livewire/client/shipping-fees-list.blade.php` — display Dubai price for UAE items
- `app/Models/ShippingFeeItem.php` — add `price_per_kg_dubai` to fillable
