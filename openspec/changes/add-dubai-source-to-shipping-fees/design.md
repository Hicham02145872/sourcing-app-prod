## Context

The admin shipping fees edit page has three transport tabs (air/sea/train) with one price column (`price_per_kg`) per item row. The client side already distinguishes direct (China) and indirect (Dubai/UAE) shipping — items with "FROM DUBAI" in their `item_style` are moved to the UAE hub tab. However, admins can only set one price per item, so they cannot configure separate Dubai pricing.

## Goals / Non-Goals

**Goals:**
- Add a `price_per_kg_dubai` column to `shipping_fee_items` (nullable decimal)
- Show two price columns per row in the admin edit page: "Direct (China)" and "Indirect (Dubai)"
- Persist both values on save, allow null for either
- Client side displays `price_per_kg_dubai` for UAE hub items, falling back to `price_per_kg`

**Non-Goals:**
- Not creating separate `estimation_days_dubai` — estimation is shared between both sources
- Not changing the database unique constraint (still one row per transport_type + item_style)
- Not adding separate Dubai tabs; prices are columns within each existing tab

## Decisions

**Decision 1: New column `price_per_kg_dubai` on `shipping_fee_items`**
- Type: `decimal(10, 2)` nullable
- Add via migration named `add_price_per_kg_dubai_to_shipping_fee_items_table`
- Add to `$fillable` on `ShippingFeeItem` model

**Decision 2: Admin edit view — two price columns per row**
- Duplicate the existing price `<td>` block
- First column labeled "Direct (China)" bound to `price_per_kg`
- Second column labeled "Indirect (Dubai)" bound to `price_per_kg_dubai`
- Both show the currency code prefix (`{{ $currency }}`)

**Decision 3: Admin Livewire — handle new field**
- `initItemsData()`: add `'price_per_kg_dubai' => null` to default items
- `mount()`: read `price_per_kg_dubai` from existing items
- `addCategory()`: include `'price_per_kg_dubai' => null` in new row
- `save()`: include `price_per_kg_dubai` in the item payload

**Decision 4: Client display — use Dubai price for UAE items**
- `ShippingFeesList` blade: when rendering items under the train/UAE tab, use `$item->price_per_kg_dubai ?? $item->price_per_kg`
- `ShippingFeeController@getRatesForPopup`: include `price_per_kg_dubai` in the indirect items response
- `ShippingFeeController@getShippingFee`: include `price_per_kg_dubai` in the API response

## Risks / Trade-offs

- [Low] Existing records have `price_per_kg_dubai` = null — client falls back to `price_per_kg`, so behavior is unchanged for existing data
- [Low] Estimation days are shared — if Dubai has different delivery times, this won't capture that. Acceptable for now.
