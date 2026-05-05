# Weight Unit Selection Implementation Plan

This plan outlines the steps to allow admins to select a unit (g, kg, colis) for weights when creating a quotation and ensure it's displayed correctly throughout the app.

## Proposed Changes

### 1. Database Update
- **Migration**: Create a migration to add `weight_unit` column to the `quotations` table.
  - `weight_unit`: enum or string (g, kg, colis), default 'g'.

### 2. Backend Updates (Admin)
- #### [MODIFY] [QuotationController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/QuotationController.php)
  - Update `store` and `update` methods to validate and save the `weight_unit`.
- #### [MODIFY] [Quotation.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/Quotation.php)
  - Add `weight_unit` to `$fillable` array.

### 3. Frontend Updates (Admin)
- #### [MODIFY] [admin/quotations/create.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/quotations/create.blade.php)
  - Replace the static "g" label with a `<select>` dropdown for units.
- #### [MODIFY] [admin/quotations/show.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/quotations/show.blade.php)
  - Update the display to show the weight followed by the unit.
- #### [MODIFY] [admin/quotations/index.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/quotations/index.blade.php)
  - Ensure unit is visible if listed.

### 4. Frontend Updates (Client)
- #### [MODIFY] [client/quotations/show.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/client/quotations/show.blade.php)
  - Update the display to show the weight with its unit.

## Verification Plan
### Manual Verification
- Create a quotation with different units (g, kg, colis) and verify they are saved correctly.
- Check the admin detail page to see if the unit matches.
- Check the client detail page to see if the unit matches.
