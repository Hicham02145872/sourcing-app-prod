# Tracking Logic Integration Plan

This plan outlines the changes required to implement conditional tracking number logic based on the Sourcing Origin (China vs. Dubai), as requested.

## Goal Description
The goal is to refine the tracking number functionality in the Sourcing App to respect the following rules:
1.  **Origin: China**: Tracking number is used and valid *only* until the order reaches 'Arrival UAE' (Dubai). From 'Arrival UAE' to the final destination, status updates are handled manually.
2.  **Origin: Dubai**: No automated tracking number is used. The entire process from 'Pending' to 'Delivered' is managed manually.

## User Review Required
> [!IMPORTANT]
> **Sourcing Origin Source of Truth**: This plan assumes `Quotation->actual_sourcing_location` (or `SourcingRequest->sourcing_location` as fallback) is the determinant for "China" vs. "Dubai". Please confirm this is correct.

> [!NOTE]
> **Automation**: Currently, there is an *on-demand* tracking check (client clicking "Track"). This plan proposes adding/modifying logic to enforce the "stop at Dubai" rule for China orders and "process manually" rule for Dubai orders. If you intended to have a background job *auto-update* statuses, please specify. This plan focuses on the *logic* and *visibility* of tracking data.

## Proposed Changes

### 1. Database & Model Verification
- **Verify Sourcing Origin**: Ensure we can reliably detect "China" vs "Dubai" from `SourcingOrder`.
    - Access via `$sourcingOrder->quotation->actual_sourcing_location` or `$sourcingOrder->quotation->sourcingRequest->sourcing_location`.

### 2. Admin Interface (Livewire Workflow)
- **File**: `app/Livewire/Admin/SourcingOrderWorkflow.php` & `resources/views/livewire/admin/sourcing-order-workflow.blade.php`
- **Change**:
    - Add a computed property or helper to determine `isSourcingFromChina` and `isSourcingFromDubai`.
    - **China**: Show Tracking Number input. Add a visual hint: "Tracking active until Arrival UAE".
    - **Dubai**: Hide Tracking Number input OR show a warning "Manual Tracking Only".

### 3. Status Dropdown Filtering (New Requirement)
- **Logic**: 
    - If **Origin = China**: Suppress manual selection of statuses between `shipment_preparing` and `arrival_uae` (inclusive) from the dropdown. 
    - **Reason**: These statuses are expected to be updated automatically via the Tracking API logic outlined below.
    - **Exception**: Allow Super Admin override or provide a specific "Manual Override" toggle/mode if automation fails.
    - If **Origin = Dubai**: Suppress `in_transit_china` from the dropdown (physically impossible).

### 4. Client Tracking Logic (Controller & Service)
- **File**: `app/Http/Controllers/Client/TrackingController.php`
- **Change**: Modify `seventeenTrackData` (or the unified data endpoint) to implement the cutoff logic.
    - Fetch Order associated with the tracking number (if possible, or pass Order ID).
    - **Condition**:
        - If **Origin = Dubai**: Return empty/manual response (or 404 for tracking API).
        - If **Origin = China**:
            - Fetch 17Track data.
            - **Filter Logic**: Check the latest status event from 17Track.
            - If the Order Status in the system is already `>= arrival_uae`, do **not** show further updates from 17Track (or show them but clearly separate them, depending on preference. The requirement says "manually from Dubai", implying we ignore the carrier's updates after that point).
            - *Interpretation*: The tracking number might actually *stop* working or be irrelevant after Dubai (e.g., handed over to local courier without link).
            - **Action**: Stops displaying API tracking events if the system status indicates it has passed the Dubai checkpoint.

### 4. Background Status Automation (Optional/Future)
*If you plan to implement a background job to auto-update statuses:*
- Create a Job: `UpdateSourcingOrderTrackingStatus`
- **Logic**:
    ```php
    if ($order->isOriginDubai()) {
        return; // Manual only
    }
    if ($order->isOriginChina()) {
        $trackingData = 17Track->get($tracking_number);
        $newStatus = mapToInternalStatus($trackingData);
        
        // STOP auto-update if current status is already >= 'arrival_uae'
        if (statusOrder($order->status) >= statusOrder('arrival_uae')) {
            return; // Manual handover
        }
        
        $order->update(['status' => $newStatus]);
    }
    ```

## Verification Plan

### Manual Verification
1.  **Test Case 1: China Origin**
    - Create a Sourcing Request/Order with Origin "China".
    - Add a valid Tracking Number.
    - Verify Tracking Input is visible in Admin.
    - **Client View**: Check that tracking details appear.
    - **Boundary Test**: Manually set status to `arrival_uae`. Verify that Client View stops showing API updates (or shows "Handed over for local delivery").

2.  **Test Case 2: Dubai Origin**
    - Create a Sourcing Request/Order with Origin "Dubai".
    - Verify Tracking Input is hidden/disabled in Admin (or marked optional).
    - **Client View**: Verify that no 17Track API call is made, or it returns a "Manual Tracking" message.

### Automated Tests
- Run existing tests: `php artisan test`
- Create new test: `tests/Feature/TrackingLogicTest.php`
    - Test `isFromChina` and `isFromDubai` helpers.
    - Test Controller logic returns correct data structure based on origin.
