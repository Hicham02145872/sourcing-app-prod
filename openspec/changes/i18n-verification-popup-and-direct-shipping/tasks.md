## 1. Product-Warning Popup i18n

- [ ] 1.1 Replace French-keyed `__()` calls in `product-warning-popup.blade.php` with English keys
- [ ] 1.2 Add English translations (identity mapping) to `lang/en.json`
- [ ] 1.3 Add French translations to `lang/fr.json`
- [ ] 1.4 Add Arabic translations to `lang/ar.json`
- [ ] 1.5 Verify the popup renders correctly in all three locales

## 2. Database Migration for Direct Shipping Flag

- [ ] 2.1 Create migration to add `is_direct` boolean column (default false) to `countries` table
- [ ] 2.2 Add `is_direct` to `$fillable` in `Country` model
- [ ] 2.3 Run the migration

## 3. Admin: Direct Shipping Toggle in Edit Page

- [ ] 3.1 Add Livewire property `isDirect` to the shipping fee edit component
- [ ] 3.2 Add toggle UI in the "Location Overview" card of `shipping-fee-edit.blade.php`
- [ ] 3.3 Include `is_direct` in the save logic
- [ ] 3.4 Hide the "Indirect (Dubai)" column in the rates table when `is_direct` is true

## 4. Client: Direct Shipping Conditional UI

- [ ] 4.1 Update `getRatesForPopup` in `ShippingFeeController.php` to return empty indirect when country is direct
- [ ] 4.2 Update the routing popup in `create.blade.php` to conditionally show/hide indirect shipping card based on `is_direct` flag
- [ ] 4.3 Update `ShippingFeesList.php` and `shipping-fees-list.blade.php` to hide Dubai pricing rows for direct countries

## 5. Verification

- [ ] 5.1 Run existing tests to confirm no regressions
- [ ] 5.2 Manually verify the complete flow for both direct and indirect countries
