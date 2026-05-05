# Hybrid Tracking Integration Plan (17TRACK + External Links)

This plan outlines how to support tracking for carriers not integrated with 17TRACK (like Choice Express and ITDida) alongside our existing 17TRACK implementation.

## 1. Overview
Not all shipments can be tracked via 17TRACK JSON API. Some logistics companies only provide web-based tracking portals. We need a way to:
- Detect if a tracking number belongs to 17TRACK or an external portal.
- Display a unified UI that either shows the timeline (17TRACK) or a "View on Carrier Site" button (External).

## 2. Carriers and External Links
Based on requirements, we will handle:
- **17TRACK**: GCC56, J&T Express, and others.
- **External Portal 1 (Choice Express)**: `https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp?trackNo={NUMBER}`
- **External Portal 2 (ITDida/YDL)**: `https://ydl.itdida.com/query.xhtml?danHao={NUMBER}`

## 3. Implementation Strategy

### A. Database Updates
We should update the `sourcing_orders` table to store the "Tracking Type".
- `tracking_type`: `17track` or `external_link`.
- `tracking_url`: Optional field for external links.

### B. Backend Logic (`TrackingController`)
The `seventeenTrackData` method should be updated to check for known external patterns before calling the 17TRACK API.

```php
// Example logic
if (str_starts_with($number, 'CHOICE')) {
    return response()->json([
        'type' => 'external',
        'url' => 'https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp?trackNo=' . $number
    ]);
}
```

### C. Frontend UI Updates (`17track.blade.php`)
The tracking page should handle the new response types:
- **If 17TRACK JSON**: Show the premium timeline as currently implemented.
- **If External Link**: Show a prominent card with a "View Tracking on Official Website" button and instructions.

## 4. Proposed Workflow
1. **User enters number.**
2. **System checks for patterns (regex).**
3. **Choice 1**: Pattern matches an external carrier -> Display link.
4. **Choice 2**: No pattern match -> Query 17TRACK API.
5. **Choice 3**: 17TRACK fails -> Offer manual external search or "Not found" state.

## 5. Next Steps
- [ ] Add `tracking_type` and `tracking_url` to `sourcing_orders`.
- [ ] Update Admin UI to allow selecting "External Link" for tracking.
- [ ] Update `17track.blade.php` to support the "External Link" view state.
