## Why

The quality selection cards on the sourcing request detail page show only a subtle border-color change when selected, making it hard for users to immediately see which option is active. Separately, the payment proof upload area does not display payment method info, forcing users to scroll up and cross-reference between two separate cards. Both issues create friction in the order flow.

## What Changes

- **Quality selection cards**: Replace the subtle border-only selected state with a visually prominent selection indicator (filled background, checkmark badge, or similar) so the active choice is immediately obvious at a glance.
- **Payment proof upload**: Display payment method details at the top of the upload proof area, so users can see where to send payment before uploading their proof — without having to scroll between separate sections.

## Capabilities

### New Capabilities
- `visible-quality-selection`: Make the selected quality option visually obvious via a prominent indicator (e.g., filled background, checkmark, or highlight treatment) on the client sourcing request detail page.
- `payment-info-above-proof`: Show payment method details above the payment proof upload input on both the sourcing order detail page and the sourcing request (quotation acceptance) page, so users can reference account info before uploading.

### Modified Capabilities
- (none — purely frontend UI enhancement, no requirement changes to existing capabilities)

## Impact

- `resources/views/client/sourcing-requests/show.blade.php`: Quality selection cards (lines 531-586) and payment proof upload area (lines 588-618)
- `resources/views/client/sourcing-orders/show.blade.php`: Payment proof upload area (lines 454-494)
- No backend changes, no controller/model changes, no new routes
