## Context

The sourcing request detail page (`show.blade.php`) has a quality selection section using radio-button cards that only change border color on selection — users report the active choice is not obvious at a glance. The same page and the sourcing order detail page both have separate "Payment Methods" and "Payment Proof Upload" cards, requiring vertical scrolling to cross-reference account details.

## Goals / Non-Goals

**Goals:**
- Make the selected quality card visually unmistakable (filled background + checkmark)
- Display payment method details inside the upload proof area on both pages
- All changes are pure Blade/Alpine/CSS — no backend modifications

**Non-Goals:**
- No changes to the payment methods CRUD, models, controllers, or routes
- No changes to the admin-side quality options configuration
- No changes to the compact quality selector on the quotations index page
- No new JavaScript libraries or dependencies

## Decisions

1. **Quality selection: filled background + checkmark badge over ring**
   - *Alternative considered*: Adding a thick outline or shadow — less visible at small card sizes
   - *Rationale*: An orange filled background with white text + a checkmark icon badge at the top-right corner makes the selected card impossible to miss, even on mobile
   - Implementation: Toggle between "selected" and "default" class sets via Alpine `:class`

2. **Payment info above upload: inline "Selected Method" details panel**
   - *Alternative considered*: Merging the two cards into one — requires structural layout change and risks losing the accordion expand/collapse UX
   - *Rationale*: Keep the existing expandable payment methods card as-is; add a compact read-only payment details panel inside the upload form showing the last-expanded or first available payment method
   - Implementation: Use Alpine `x-data` to track a `selectedPaymentMethod`; when a method is expanded in the methods card, its details appear in the upload section. Show the first active method's details by default.

## Risks / Trade-offs

- **Payment details duplication**: The info appears both in the methods card (expanded) and above the upload input — this is intentional for convenience but adds minor visual redundancy
- **Alpine.js state sharing**: The payment methods card and upload form are sibling elements. Both use `x-data` with separate scopes. A shared parent `x-data` or a simple event-based sync approach is needed — using a shared Alpine scope at the parent grid level is the simplest approach
