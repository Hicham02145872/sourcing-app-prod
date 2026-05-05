# Sales Margin Report File Mapping

This document lists all files related to the Sales Margin Reporting system.

## 1. Logic & Calculation
- **Controller**: [ReportController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/ReportController.php) - Calculates margins based on purchase cost and selling price across orders.
- **Routes**: [web.php](file:///c:/xampp/htdocs/sourcing-app/routes/web.php) - Look for `admin.reports.sales-margin`.

## 2. UI & Interaction
- **Blade Template**: [sales-margin.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/admin/reports/sales-margin.blade.php) - Data visualization, tables, and filters for reporting.
- **Sidebar**: [sidebar.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/components/sidebar.blade.php) - Admin link to the report page.

## 3. Documentation
- [SALES_REPORTING_SYSTEM_PLAN.md](file:///c:/xampp/htdocs/sourcing-app/SALES_REPORTING_SYSTEM_PLAN.md) - Design and logic plan for the reporting system.
