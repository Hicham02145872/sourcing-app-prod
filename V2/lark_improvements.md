# Lark Integration V2: Improvements & Separation Plan

This document outlines the proposed improvements for the Lark Suite integration, aiming for feature parity with the Google Sheets integration and a cleaner, more separated user interface for Shipping Companies.

## 1. Feature Parity with Google Sheets

Currently, the Google Sheets integration is more advanced than Lark. V2 will bridge this gap.

### A. Conditional Formatting (Status Colors)
*   **Goal**: Automatically color-code rows based on the order status in Lark Sheets.
*   **Implementation**: Use the Lark `condition_formats` API to apply `back_color` and `fore_color` based on the "Status" column value.
*   **Colors**: Matching the existing logic in `GoogleSheetService.php` (e.g., Green for Delivered, Yellow for Pending, Red for Canceled).

### B. Data Validation (Status Dropdowns)
*   **Goal**: Provide a dropdown menu for the "Status" column in Lark Sheets.
*   **Implementation**: Use the Lark Spreadsheet Data Validation API to restrict values to the `SourcingOrder::STATUSES`.

### C. Batch Synchronization
*   **Goal**: Allow syncing multiple orders at once or re-syncing entire sheets efficiently.
*   **Implementation**: Implement `batchUpsertRows` in `LarkSheetService.php` using the Lark `values_batch_update` endpoint.

## 2. Shipping Company Interface Cleanup

The current "Shipping Company" management interface is cluttered because it displays fields for both Google and Lark integrations simultaneously.

### A. Dynamic Interface
*   **Toggle-Based UI**: Add an "Integration Type" selector (Google Sheets vs. Lark Suite).
*   **Conditional Fields**: Only show the fields relevant to the selected integration.
    *   **Google Fields**: Sheet ID, Sheet Name.
    *   **Lark Fields**: App ID, App Secret, Base Token, Table ID.
*   **Clean List View**: In the companies table, show a clear badge indicating which integration is active.

### B. "Connection Test" Improvements
*   Standardize the connection test UI for both integrations.
*   Provide clearer error messages when tokens or permissions are missing.

## 3. Google Sheets and Lark Separation

To reach a better architectural separation:

### A. Service Layer Refactoring
*   **Base Interface**: Create a `SheetIntegrationInterface` defining methods like `syncOrder`, `ensureHeaders`, `testConnection`.
*   **Implementation**: `LarkSheetService` and `GoogleSheetService` will implement this interface.
*   **Contextual Factory**: Use a factory or service container binding to resolve the correct service based on the Shipping Company's configuration.

### B. Model Refactoring (Long-term)
*   Instead of having all fields in the `shipping_companies` table, move integration-specific settings to a polymorphic `integration_settings` table or a `settings` JSON column.
*   *Note: This might be a breaking change, so a JSON column is preferred for transition.*

## 4. Proposed Timeline & Tasks
1.  **Refactor `LarkSheetService`**: Add conditional formatting and data validation logic.
2.  **Update `ShippingCompanyManager`**: Implement the dynamic fields UI.
3.  **Implement Batch Sync for Lark**.
4.  **Verification**: Test both integrations side-by-side.
