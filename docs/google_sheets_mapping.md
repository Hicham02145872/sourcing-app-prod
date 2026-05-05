# Google Sheets Integration File Mapping

This document lists all files related to the Google Sheets integration functionality.

## 1. Services & Logic
- **Service**: [GoogleSheetService.php](file:///c:/xampp/htdocs/sourcing-app/app/Services/GoogleSheetService.php) - Core logic for interacting with the Google Sheets API.
- **Job**: [SyncOrderToGoogleSheetJob.php](file:///c:/xampp/htdocs/sourcing-app/app/Jobs/SyncOrderToGoogleSheetJob.php) - Background job for synchronizing orders.
- **Listener**: [SyncOrderToGoogleSheet.php](file:///c:/xampp/htdocs/sourcing-app/app/Listeners/SyncOrderToGoogleSheet.php) - Event listener that triggers the sync.
- **Event Registration**: [EventServiceProvider.php](file:///c:/xampp/htdocs/sourcing-app/app/Providers/EventServiceProvider.php) - Maps events to listeners.

## 2. Models & Data
- **Settings Model**: [GoogleSheetSetting.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/GoogleSheetSetting.php) - Stores Sheet ID and related configurations.
- **Log Model**: [GoogleSheetSyncLog.php](file:///c:/xampp/htdocs/sourcing-app/app/Models/GoogleSheetSyncLog.php) - Records history and status of synchronizations.
- **Seeder**: [GoogleSheetSettingSeeder.php](file:///c:/xampp/htdocs/sourcing-app/database/seeders/GoogleSheetSettingSeeder.php) - Initial configuration for the settings.

## 3. Controllers & UI
- **Admin Settings Controller**: [GoogleSheetSettingsController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/GoogleSheetSettingsController.php) - Manages the Google Sheets settings page.
- **Order Controller**: [SourcingOrderController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Admin/SourcingOrderController.php) - Triggers sync during order updates.
- **Routes**: [web.php](file:///c:/xampp/htdocs/sourcing-app/routes/web.php) - Admin routes for configuration.

## 4. Documentation
- [google-sheets-analysis.md](file:///c:/xampp/htdocs/sourcing-app/docs/google-sheets-analysis.md)
- [google_sheet_to_app_sync.md](file:///c:/xampp/htdocs/sourcing-app/docs/google_sheet_to_app_sync.md)
