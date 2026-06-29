## 1. Database migrations

- [x] 1.1 Create migration to add `price_per_kg_china_to_dubai` and `price_per_kg_dubai_to_africa` columns to `shipping_fee_items` table (decimal 10,2 nullable)
- [x] 1.2 Add both new columns to `$fillable` in `app/Models/ShippingFeeItem.php`
- [x] 1.3 Create migration for `delivery_properties` table (id, delivery_type, title, description, icon, sort_order, is_active, timestamps)
- [x] 1.4 Create migration for `delivery_defects` table (id, delivery_type, title, description, sort_order, is_active, timestamps)
- [x] 1.5 Create migration for `delivery_notices` table (id, delivery_type, body_text, is_active, timestamps)
- [x] 1.6 Create Eloquent models for `DeliveryProperty`, `DeliveryDefect`, `DeliveryNotice`

## 2. Admin shipping fee edit — split indirect cost fields

- [x] 2.1 Update `ShippingFeeEdit.php` Livewire component: add `price_per_kg_china_to_dubai` and `price_per_kg_dubai_to_africa` to default items in `initItemsData()` and `addCategory()`
- [x] 2.2 Read both new fields from existing items in `mount()` in `ShippingFeeEdit.php`
- [x] 2.3 Include both new fields in the save payload in `ShippingFeeEdit.php`
- [x] 2.4 Update `shipping-fee-edit.blade.php`: add two vertically-stacked input fields under the "Indirect (Dubai)" column for China→Dubai and Dubai→Africa

## 3. Partner page — client-side display of split costs

- [x] 3.1 Update `ShippingFeesList` Livewire component or create a new component for the partner page to expose both split cost fields
- [x] 3.2 Update the partner page view ("Nos partenaires") to display two vertically-stacked price fields per item: China→Dubai and Dubai→[Country]
- [x] 3.3 Include both new fields in `getShippingFee()` and `getRatesForPopup()` responses in `ShippingFeeController.php`
- [x] 3.4 Add translations for "China → Dubai", "Dubai → {country}" labels in en.json, fr.json, ar.json

## 4. Super Admin dashboard — delivery properties, defects, and notices

- [ ] 4.1 Create `app/Livewire/Admin/DeliveryContentManager.php` Livewire component with tabbed interface (Properties, Defects, Notices)
- [ ] 4.2 Create Livewire view `resources/views/livewire/admin/delivery-content-manager.blade.php` with CRUD forms for each tab
- [ ] 4.3 Create admin route for delivery content management under super_admin middleware
- [ ] 4.4 Create admin index view page at `resources/views/admin/delivery-content/index.blade.php`
- [ ] 4.5 Implement create/edit/delete/toggle logic for `delivery_properties` in the Livewire component
- [ ] 4.6 Implement create/edit/delete/toggle logic for `delivery_defects` in the Livewire component
- [ ] 4.7 Implement edit/save logic for `delivery_notices` (single active notice per type) in the Livewire component
- [ ] 4.8 Create a shared Blade partial or include for rendering delivery properties/defects/notices in client popups
- [ ] 4.9 Wire up the client-facing popup to display dynamic content from the three tables

## 5. Quotation — display full delivery address

- [ ] 5.1 Update `admin/quotations/show.blade.php`: add "Delivery Address" column in the Destinations table showing `$dest->address` with fallback to `$dest->label_address`
- [ ] 5.2 Update `admin/quotations/edit.blade.php`: add read-only display of delivery address in the Product Specifications section for each destination
- [ ] 5.3 Update `admin/sourcing-requests/show.blade.php` if applicable to also show the full address

## 6. Real photo viewer and per-photo delete in admin

- [ ] 6.1 Create an Alpine.js modal component or Blade include for full-size photo viewer (click to open, Escape to close)
- [ ] 6.2 Update `admin/quotations/edit.blade.php`: add click-to-view on `real_product_image` and each `quotation_media` image using the photo viewer modal
- [ ] 6.3 Create `QuotationMediaController@destroy` route and method for individual media deletion
- [ ] 6.4 Add standalone "Delete" button per media item in the quotation edit view (replacing checkbox mechanism)
- [ ] 6.5 Implement delete confirmation dialog and file cleanup (delete from storage + DB)
- [ ] 6.6 Add delete functionality for `real_product_image` (set to NULL on delete)

## 7. Unlimited attachments

- [ ] 7.1 Remove `max:10` constraint from `'files'` validation rule in `SourcingOrderController@uploadMedia()`
- [ ] 7.2 Remove any file count display limit text from the sourcing order media upload UI
- [ ] 7.3 Verify quotation create/update controllers already have no file count limit (no changes needed if already unlimited)

## 8. Tests

- [ ] 8.1 Test that admin can save and retrieve split indirect cost fields (`price_per_kg_china_to_dubai`, `price_per_kg_dubai_to_africa`)
- [ ] 8.2 Test that partner page displays both split cost fields for countries with indirect shipping
- [ ] 8.3 Test Super Admin delivery properties CRUD (create, read, update, delete, toggle)
- [ ] 8.4 Test that delivery address is displayed in quotation show and edit views
- [ ] 8.5 Test photo viewer modal opens on click and closes on Escape
- [ ] 8.6 Test per-photo delete removes file from storage and database
- [ ] 8.7 Test that more than 10 files can be uploaded to a sourcing order
