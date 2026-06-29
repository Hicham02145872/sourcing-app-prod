## Why

The existing shipping fee system consolidates China→Dubai→Africa costs into a single "Indirect (Dubai)" price, which does not provide clients with sufficient transparency about how costs are distributed. Additionally, the Super Admin lacks dynamic control over delivery-related popup content (benefits, defects, discharge notices), product photos lack management features (viewer, selective deletion), the quotation interface omits the customer's final delivery address, and attachment limits restrict operational flexibility. These gaps reduce transparency, control, and usability for both clients and administrators.

## What Changes

- **Partner page delivery cost split**: On the "Nos partenaires" page, replace the single grouped indirect delivery price with two vertically-stacked fields: (1) China→Dubai cost and (2) Dubai→Africa cost, dynamically linked to database currency and pricing data per destination country.
- **Super Admin delivery properties dashboard**: New Super Admin interface to manage dynamic popup content: custom delivery properties/benefits, logistics defects/limitations (e.g., battery/liquid restrictions), and editable discharge notice text — all stored in the database and rendered without code changes.
- **Quotation address display**: Add the client's exact final delivery address (from their shipping calculator input) to the quotation/pricing interface alongside existing fields (company name, destination, country).
- **Real photo viewer and delete**: In the admin quotation/pricing interface, add click-to-view full-size real product photos and a "Delete" button per photo that removes the image from both server and client-facing interface.
- **Unlimited attachments**: Remove file count limits on product media uploads (quotation_media, sourcing_order_media) so admins can attach unlimited images per product.

## Capabilities

### New Capabilities
- `partner-page-delivery-costs`: Split indirect delivery cost display on the partner page into two separate fields (China→Dubai, Dubai→Africa), linked dynamically to country-specific pricing and currency data
- `super-admin-delivery-properties`: Super Admin dashboard providing CRUD for delivery popup properties/benefits, logistics defects/limitations, and editable discharge notice text
- `quotation-address-display`: Display the client's complete final delivery address in the admin quotation/pricing interface
- `real-photo-viewer-delete`: Full-size photo viewer and per-photo delete functionality for real product images in the admin dashboard
- `unlimited-attachments`: Remove maximum file count constraints from product media upload validation and UI

### Modified Capabilities
- `shipping-fees-dubai-source`: Extend requirements to support a two-part cost structure (China→Dubai and Dubai→Africa components) for indirect shipping, with both values stored and displayed independently on the partner page

## Impact

- **Database**: New tables for delivery properties/defects/notices; possible new columns on `shipping_fee_items` or a related table for split costs; schema adjustments for attachment limits
- **Controllers**: New or modified controllers for Super Admin delivery properties CRUD; modifications to quotation controller for address display and photo management
- **Livewire Components**: New component for Super Admin delivery properties dashboard; modifications to existing quotation/pricing components for address display and photo viewer/delete
- **Views/Blade**: New Super Admin views for delivery property management; modifications to partner page, quotation edit/show views, and client shipping display
- **API/Responses**: Shipping fee API responses may need to include split cost components
- **Validation**: Remove `max:N` constraint from file upload validation rules for attachments
- **Storage/Media**: Delete operation for quotation_media must clean up server files; photo viewer requires no additional infrastructure (uses existing `media_url`)
