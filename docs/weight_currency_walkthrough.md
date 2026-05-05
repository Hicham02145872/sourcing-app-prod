# Weight Unit & Currency Improvements Walkthrough

I have implemented dynamic weight units and improved the currency selection experience on the quotation pages.

## Key Changes

### 1. Dynamic Weight Units
Admins can now select between `g`, `kg`, and `colis` when specifying the weight of a product in a quotation.

- **Form Update**: Added a dropdown menu next to the weight input field in the "Create Quotation" page.
- **Data Persistence**: Created a database migration to store the `weight_unit` and updated the backend logic to handle it.
- **Display Updates**: Both the Admin and Client views now show the weight followed by the correct unit (e.g., "5.00 kg" or "10 colis").

### 2. Live Currency Symbols
The currency symbols on the creation form now change automatically based on the selected currency.

- **Real-time Updates**: When an admin selects "USD", symbols change to `$`. Selecting "EUR" changes them to `€`, and "MAD" updates them accordingly.
- **Profit Calculation**: The live profit estimation footer also uses the selected currency symbol for absolute clarity.

## Files Modified

- [Quotation.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/Quotation.php)
- [QuotationController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/QuotationController.php)
- [create.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/quotations/create.blade.php)
- [admin/show.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/quotations/show.blade.php)
- [client/show.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/client/sourcing-orders/show.blade.php)
- [Migration](file:///c:/xampp/htdocs/sourcing-app/database/migrations/2025_12_17_234634_add_weight_unit_to_quotations_table.php)

## Verification Results
- [x] Migration ran successfully.
- [x] Weight unit is saved correctly in the database.
- [x] Currency symbols update instantly on the creation page.
- [x] Unit/Symbol consistency across Admin and Client views.
