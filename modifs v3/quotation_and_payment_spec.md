# Specification: Sourcing Application Enhancements & Batch Payments

This specification defines the implementation details for addressing requests #3, #4, #5, #6, and #7 in the sourcing application.

---

## 1. Feature 3: Batch Payment / Selection UI
**Requirement:** When the client has multiple quoted requests, they should have the option to select them, see the total amount, and pay for them together.

### Current Status
- The client-side quotations list index view (`resources/views/client/quotations/index.blade.php`) already includes checkbox selections and a sticky payment drawer.
- The controller (`app/Http/Controllers/Client/QuotationController.php`) has a `bulkPaymentShow` and `bulkPay` method.

### Actions Needed
- Refine the checkbox logic in `resources/views/client/quotations/index.blade.php` to ensure it only appears for valid, non-negotiating, non-accepted quotations.
- Update the client quotations list styling to make selection highly intuitive and visually appealing.

---

## 2. Feature 4: Keep Payment and Upload Options Visible During Negotiation
**Requirement:** When a quotation is in the `negotiating` state, the client should still be able to accept it and upload proof of payment (since the admin might refuse the negotiation and the client decides to pay the current price).

### Actions Needed
- **Controller updates** (`app/Http/Controllers/Client/QuotationController.php`):
  - In `bulkPaymentShow` and `bulkPay`, allow quotations with status `negotiating` to be accepted and paid.
- **View updates** (`resources/views/client/quotations/index.blade.php`):
  - Show the checkbox selection and "Accept Quotation" button even if the quotation is in `negotiating` status.
  - Display a distinct badge or helper text indicating "Negotiating" or "Best Price Negotiating" but keep it selectable.
- **View updates** (`resources/views/client/sourcing-requests/show.blade.php`):
  - Ensure the "Authorize & Initialize Order" (Accept) button remains visible when the quotation status is `negotiating`.


---

## 3. Feature 5: Quality-Based Pricing (Low, Medium, Good Quality)
**Requirement:** When creating a quotation, the admin should be able to provide different quality pricing levels (Low quality, Medium quality, Good quality), each containing a price and a photo. When viewing the quotation, the client should see these options alongside the request details, select one quality option, and proceed to order and pay for it.

### DB Schema Changes
We will create a new migration to add a `quality_options` (JSON or Text) column to the `quotations` table.
The structure of this column will be:
```json
{
  "low": {
    "price": 10.50,
    "image_path": "quotations/quality/low_xxx.jpg"
  },
  "medium": {
    "price": 15.00,
    "image_path": "quotations/quality/medium_xxx.jpg"
  },
  "good": {
    "price": 20.00,
    "image_path": "quotations/quality/good_xxx.jpg"
  }
}
```

### Admin Creation/Edit Form (`admin/quotations/create.blade.php` and `edit.blade.php`)
- Add a new "Quality Pricing Options" section with input fields for Low, Medium, and Good quality:
  - Unit Price (e.g. `quality_options[low][price]`)
  - Image upload (e.g. `quality_options_images[low]`)
- If quality prices are entered, they will override or be saved in `quality_options`.

### Admin Controller (`app/Http/Controllers/Admin/QuotationController.php`)
- Validate and store these pricing options.
- Upload quality-specific images and store their public paths.

### Client Request Show View (`resources/views/client/sourcing-requests/show.blade.php`)
- Display the sourcing request details as usual.
- If the quotation contains `quality_options`:
  - Show three cards for Low, Medium, and Good quality with their respective photos and prices.
  - Allow the client to select one option using a radio input or active card state.
  - Submit the selected option (e.g., `selected_quality=medium`) when accepting the quotation.
- The `accept` method in `QuotationController.php` will update the quotation's primary `unit_price`, `amount`, and `real_product_image` with the chosen quality level's values before creating the `SourcingOrder`!

---

## 4. Feature 6: Admin Multi-photo Support, View, Modify, and Delete
**Requirement:** In the quotation creation/edit view, support multiple photos, and allow the admin to view, modify (replace), and delete existing ones.

### Actions Needed
- The `update` method in `QuotationController` already supports a `delete_media` list to delete specific `QuotationMedia` records.
- In `admin/quotations/edit.blade.php`:
  - Display the uploaded media gallery with a thumbnail for each image/video.
  - Add a "Delete" button to each media item, which registers its ID in a hidden `delete_media[]` array to be deleted upon submission.
- In both `create.blade.php` and `edit.blade.php`:
  - Enhance the dropzone/multiple file selection with a modern, dynamic JavaScript preview list. Let the admin remove files from the preview list before they upload them.

---

## 5. Feature 7: Synchronize Sourcing Location (China / Dubai) on Admin Side
**Requirement:** If the admin changes the sourcing location in the quotation (e.g., China to Dubai), this change shows up on the client side, but on the admin side show views (sourcing request and sourcing order) it does not reflect.

### Actions Needed
- **Admin Sourcing Request Show View** (`resources/views/admin/sourcing-requests/show.blade.php`):
  - Check if `$sourcingRequest->quotation` has `actual_sourcing_location`.
  - If it differs from `$sourcingRequest->sourcing_location`, display it clearly as "Alternative Sourcing Location" (similar to the client side).
- **Admin Sourcing Order Show View** (`resources/views/admin/sourcing-orders/show.blade.php`):
  - Add a "Sourcing Location" detail row next to "Shipping Method".
  - Read `actual_sourcing_location` from the quotation, falling back to the original request sourcing location.
