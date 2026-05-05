# Assignment Functionality File Mapping

This document lists all files related to the admin assignment system for requests, quotations, and orders.

## 1. Core Models & Observers
- [User.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/User.php) - Defines 'admin' roles and their capacities.
- [SourcingRequest.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/SourcingRequest.php) - Contains `assigned_to_admin_id`.
- [Quotation.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/Quotation.php) - Recently updated to include `assigned_to_admin_id`.
- [SourcingOrder.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/SourcingOrder.php) - Tracks assigned admin for order fulfillment.
- [SourcingRequestObserver.php](file:///c:/xampp/htdocs/sourcing-app/app/Observers/SourcingRequestObserver.php) - Handles automatic assignment logic (Round Robin/Load Balance).

## 2. Controllers (Admin)
- [AdminSourcingRequestController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/AdminSourcingRequestController.php) - Handles manual and automatic assignment for requests.
- [QuotationController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/QuotationController.php) - Manages assignment synchronization between requests and quotes.
- [SourcingOrderController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/SourcingOrderController.php) - Handles order specific assignments.

## 3. Database & Migrations
- [...add_assigned_to_admin_id_to_sourcing_requests_table.php](file:///c:/xampp/htdocs/sourcing-app/database/migrations/2025_12_13_012649_add_assigned_to_admin_id_to_sourcing_requests_table.php)
- [...add_assigned_to_admin_id_to_sourcing_orders_table.php](file:///c:/xampp/htdocs/sourcing-app/database/migrations/2025_12_15_234542_add_assigned_to_admin_id_to_sourcing_orders_table.php)
- [...add_assigned_to_admin_id_to_quotations_table.php](file:///c:/xampp/htdocs/sourcing-app/database/migrations/2025_12_17_204000_add_assigned_to_admin_id_to_quotations_table.php)

## 4. UI Components
- [admin/sourcing-requests/index.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/sourcing-requests/index.blade.php) - Filtering by admin.
- [admin/sourcing-requests/show.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/sourcing-requests/show.blade.php) - Assignment UI components.

## 5. Console
- [SyncSourcingOrderAssignments.php](file:///c:/xampp/htdocs/sourcing-app/app/Console/Commands/SyncSourcingOrderAssignments.php) - Utility command to sync assignments.

## 6. Documentation
- [auto-assignment-plan.md](file:///c:/xampp/htdocs/sourcing-app/docs/auto-assignment-plan.md)
