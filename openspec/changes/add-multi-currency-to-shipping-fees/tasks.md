## 1. Shipping fees index table — add currency column

- [x] 1.1 Add `<th>` for "Currency" in `resources/views/livewire/admin/shipping-fees-table.blade.php`
- [x] 1.2 Add `<td>` showing `$country->shippingFee->currency ?? '—'` in the table body

## 2. Excel import — add currency selector

- [x] 2.1 Add a currency `<select>` to `resources/views/admin/shipping-fees/import.blade.php` with options from `config('currencies')`, default "USD"
- [x] 2.2 Add `$currency` property and setter to `app/Imports/ShippingFeesFromAirFreightDDPImport.php`; use it instead of hardcoded `'USD'` in `create()` calls
- [x] 2.3 Pass the selected currency from the import controller (`ShippingFeeController@import`) to the import class
