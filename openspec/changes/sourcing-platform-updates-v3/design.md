## Context

The platform (Laravel 12 + Livewire 3) already handles shipping fees with two price columns (`price_per_kg` for direct China shipping and `price_per_kg_dubai` for indirect Dubai shipping) at both admin edit and client display levels. However, the indirect Dubai cost is shown as a single grouped price rather than itemized into China→Dubai and Dubai→Africa components. The partner page is currently static text; no dynamic per-partner pricing breakdown exists.

Super Admin controls for delivery-related popup content (benefits, defects, discharge notices) are hardcoded in views or absent entirely. The quotation/pricing interface lacks the customer's final delivery address. Real product photos in admin have no dedicated viewer or per-photo delete — only a checkbox batch-delete mechanism. File upload limits restrict attachment counts on some endpoints.

## Goals / Non-Goals

**Goals:**
- Add two new price columns to `shipping_fee_items` for split indirect costs (`price_per_kg_china_to_dubai`, `price_per_kg_dubai_to_africa`) with admin edit capability and client partner-page display
- Create `delivery_properties` (benefits/properties), `delivery_defects` (limitations/defects), and `delivery_notices` (discharge notice text) tables with Super Admin CRUD via a dedicated Livewire component
- Display the client's full delivery address from `sourcing_request_destinations.address` (or `sourcing_requests.address`) in the admin quotation show and edit views
- Add a lightbox-style photo viewer for `real_product_image` and `quotation_media` in admin quotation views, with individual delete buttons that immediately remove the file from server and database
- Remove `max:N` file count constraints from `uploadMedia()` and quotation media uploads; allow unlimited attachments

**Non-Goals:**
- Not restructuring the existing `price_per_kg` or `price_per_kg_dubai` columns (they remain for backward compatibility and direct pricing)
- Not adding per-shipping-company pricing (costs are per-country as before)
- Not modifying client-side shipping calculator UI beyond the partner page
- Not adding batch operations for delivery properties
- Not retroactively splitting existing `price_per_kg_dubai` values into the two new columns

## Decisions

**Decision 1: New columns for split indirect costs on `shipping_fee_items`**
- Add `price_per_kg_china_to_dubai` (decimal 10,2 nullable) and `price_per_kg_dubai_to_africa` (decimal 10,2 nullable)
- Admin edit page: add two new input columns under a grouped "Indirect (Dubai)" section, vertically stacked
- Client partner page: display both values stacked vertically with labels "China → Dubai" and "Dubai → [Country]"
- API responses (`getShippingFee`, `getRatesForPopup`): include both new fields

**Decision 2: Three new database tables for delivery content**
- `delivery_properties`: `id`, `delivery_type` (enum: direct/indirect), `title`, `description`, `icon`, `sort_order`, `is_active`
- `delivery_defects`: `id`, `delivery_type`, `title`, `description`, `sort_order`, `is_active`
- `delivery_notices`: `id`, `delivery_type`, `body_text`, `is_active` (single active row per type, or versioned)
- Super Admin dashboard: new Livewire component `DeliveryContentManager` with tabbed interface for properties/defects/notices
- Client popups: rendered dynamically from these tables via a shared Blade partial or API endpoint

**Decision 3: Address display in quotation views**
- In `admin/quotations/show.blade.php`: add a "Delivery Address" field in the Destinations table or Logistics section showing `$dest->address`
- In `admin/quotations/edit.blade.php`: add a read-only display of the address in the Product Specifications or Destinations section
- Use `sourcing_request_destinations.address` as the primary source; fall back to `sourcing_requests.address`

**Decision 4: Photo viewer and per-photo delete in admin**
- Photo viewer: use a simple Tailwind/Alpine.js modal that opens on click and shows the full-size image via `media_url()`
- Delete button: replace the checkbox-based delete with a standalone `POST`/`DELETE` button per media item, calling `QuotationMediaController@destroy` (new route) that removes the file from disk and deletes the DB record
- Apply to both `quotation_media` and `real_product_image` (the featured photo gets a viewer + delete button)
- JavaScript: no new dependencies — use existing Alpine.js for modal toggling

**Decision 5: Remove attachment count limits**
- `SourcingOrderController@uploadMedia`: change `'files' => 'required|array|min:1|max:10'` to `'files' => 'required|array|min:1'`
- `QuotationController` create/update: no changes needed (already no max count)
- Client-side validation: remove any `max:N` JS checks for file count (keep per-file size validation)

## Risks / Trade-offs

- [Low] Existing `price_per_kg_dubai` values remain unchanged — the new split columns start empty. Admins must populate them manually for each country.
- [Low] Adding delete buttons for individual media increases the risk of accidental deletion — add a confirmation dialog before executing
- [Low] Unlimited attachments could lead to storage abuse — mitigated by per-file size limits (10MB-100MB depending on endpoint) and existing disk space constraints
- [Medium] The partner page currently shows static text — creating a dynamic partner pricing view may require a new client-facing page or Livewire component. Coordinate with marketing/content team on placement
- [Low] Delivery content tables are simple key-value stores — no versioning or scheduling of content changes. Acceptable for v1
