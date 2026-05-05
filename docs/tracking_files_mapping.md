# Tracking Systems File Mapping

This document lists all files related to the tracking functionalities in the application.

## 1. 17TRACK System (New)
Integration with the 17TRACK API for global tracking (GCC56, J&T, etc.).

### Backend
- **Service**: [SeventeenTrackService.php](file:///c:/xampp/htdocs/sourcing-app/app/Services/SeventeenTrackService.php) - Handles API requests, registration, and carrier detection.
- **Controller**: [TrackingController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Client/TrackingController.php) - Methods: `seventeenTrackIndex`, `seventeenTrackData`.
- **Routes**: [web.php](file:///c:/xampp/htdocs/sourcing-app/routes/web.php) - Routes prefixed with `/client/tracking/17track`.

### Frontend
- **View**: [17track.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/client/tracking/17track.blade.php) - Premium tracking UI with timeline.
- **Navigation**: [sidebar.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/components/sidebar.blade.php) - Sidebar link for the 17TRACK page.

### Documentation
- [17track_integration_plan.md](file:///c:/xampp/htdocs/sourcing-app/docs/17track_integration_plan.md) - Initial integration strategy.
- [17track_manual_tracking_plan.md](file:///c:/xampp/htdocs/sourcing-app/docs/17track_manual_tracking_plan.md) - Manual tracking vs API differences.
- [hybrid_tracking_integration_plan.md](file:///c:/xampp/htdocs/sourcing-app/docs/hybrid_tracking_integration_plan.md) - Plan for supporting external tracking links.

---

## 2. Existing Tracking System (Legacy/Standard)
Original tracking system using the Faster.ae API.

### Backend
- **Controller**: [TrackingController.php](file:///c:/xampp/htdocs/sourcing-app/app/Http/Controllers/Client/TrackingController.php) - Methods: `index`, `data`.
- **Routes**: [web.php](file:///c:/xampp/htdocs/sourcing-app/routes/web.php) - Standard `/client/tracking` routes.

### Frontend
- **View**: [index.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/client/tracking/index.blade.php) - Standard tracking UI.
- **Navigation**: [sidebar.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/components/sidebar.blade.php) - "Track Shipment" sidebar link.

---

## 3. Shared Files
- **Layout**: [app.blade.php](file:///c:/xampp/htdocs/sourcing-app/resources/views/layouts/app.blade.php) - Main layout with Vite and script stacks.
- **Logs**: `storage/logs/laravel.log` - Crucial for debugging API responses.
