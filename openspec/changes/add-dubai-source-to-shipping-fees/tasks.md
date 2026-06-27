## 1. Database migration

- [x] 1.1 Create migration `add_price_per_kg_dubai_to_shipping_fee_items_table` adding nullable `price_per_kg_dubai` decimal column
- [x] 1.2 Add `price_per_kg_dubai` to `$fillable` in `app/Models/ShippingFeeItem.php`

## 2. Admin Livewire — handle new field

- [x] 2.1 Add `'price_per_kg_dubai' => null` to default items in `initItemsData()` and `addCategory()` in `ShippingFeeEdit.php`
- [x] 2.2 Read `price_per_kg_dubai` from existing items in `mount()` in `ShippingFeeEdit.php`
- [x] 2.3 Include `price_per_kg_dubai` in the save payload in `ShippingFeeEdit.php`

## 3. Admin view — add Indirect (Dubai) price column

- [x] 3.1 Add "Indirect (Dubai)" `<th>` in the table header in `shipping-fee-edit.blade.php`
- [x] 3.2 Add a second price `<td>` with `itemsData.*.price_per_kg_dubai` input in the table body for each transport tab

## 4. Client-side — display Dubai price

- [x] 4.1 Update `shipping-fees-list.blade.php` to show `price_per_kg_dubai ?? price_per_kg` for UAE hub items
- [x] 4.2 Include `price_per_kg_dubai` in `getShippingFee()` and `getRatesForPopup()` responses in `ShippingFeeController.php`

## 5. Tests

- [x] 5.1 Test that admin can save and retrieve `price_per_kg_dubai`
- [x] 5.2 Test that client displays Dubai price for UAE items
