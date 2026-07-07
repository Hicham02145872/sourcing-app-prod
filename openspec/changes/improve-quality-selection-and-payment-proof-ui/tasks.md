## 1. Quality Selection — Prominent Visual Indicator

- [x] 1.1 Modify `resources/views/client/sourcing-requests/show.blade.php` quality card selected state: replace `border-[#EF7722] ring-2 ring-[#EF7722]/20` with filled orange background (`bg-[#EF7722]`), white text for label and price, and appropriate dark mode styles
- [x] 1.2 Add a checkmark icon badge positioned at the top-right corner of the selected quality card (visible only when selected)
- [x] 1.3 Ensure unselected cards remain unchanged (white background, gray border, default text) and hover state still works
- [x] 1.4 Verify the Alpine `:class` binding correctly toggles between selected/unselected states on click and on page load

## 2. Payment Method Info Above Upload Proof

- [x] 2.1 In `resources/views/client/sourcing-orders/show.blade.php`: lift Alpine `selectedMethod` state to a shared parent scope so the upload section can read the selected method
- [x] 2.2 In `resources/views/client/sourcing-orders/show.blade.php`: add a compact read-only payment details panel inside the "Payment Status" card, directly above the file input, showing the selected (or first) payment method's name, logo, and all detail key-value pairs
- [x] 2.3 In `resources/views/client/sourcing-requests/show.blade.php`: add a compact read-only payment details panel inside the quotation acceptance form, directly above the "Upload Proof of Payment" file input, showing the selected (or first) payment method's name, logo, and details
- [x] 2.4 Wire the Alpine state in both views so expanding a different method in the "Available Payment Methods" card automatically updates the details panel above the upload input

## 3. Verification

- [ ] 3.1 Load the sourcing request detail page with multiple quality options; click each card and confirm the filled background + checkmark appears on the selected card and disappears from others
- [ ] 3.2 Load the sourcing order detail page with `pending_payment` status; confirm the first payment method's details appear above the upload input, and clicking a different method in the methods card updates the panel above
