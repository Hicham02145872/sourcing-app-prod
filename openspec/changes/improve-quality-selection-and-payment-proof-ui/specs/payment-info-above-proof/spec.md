## ADDED Requirements

### Requirement: Payment method details shown above payment proof upload

The system SHALL display the details of a payment method at the top of the payment proof upload area so users can see where to send payment before uploading their proof.

This applies to two pages:
- Sourcing order detail page (`sourcing-orders/show.blade.php`)
- Sourcing request detail page (`sourcing-requests/show.blade.php`)

The payment details panel SHALL:
- Be placed directly above the "Upload Proof of Payment" file input
- Show by default the first active payment method's details (name, logo, and all key-value pairs from the `details` JSON field)
- Use a compact read-only layout (not the expandable accordion style from the payment methods card)
- Be wrapped in an Alpine.js scope that reacts to the user's selection in the "Available Payment Methods" card above — when the user expands a different method in the methods card, the upload section's details panel SHALL update to show that method's info

#### Scenario: Page loads with payment methods available

- **WHEN** the sourcing order detail page loads with `$paymentMethods` containing active methods
- **THEN** the first payment method's details (name, logo, account info) SHALL appear above the upload proof input
- **AND** the existing "Available Payment Methods" card SHALL remain unchanged above the payment section

#### Scenario: User selects a different payment method

- **WHEN** the user expands a different payment method in the "Available Payment Methods" card
- **THEN** the details panel above the upload input SHALL update to show that method's name, logo, and details

#### Scenario: No payment methods configured

- **WHEN** `$paymentMethods` is empty or not loaded
- **THEN** the payment details panel SHALL NOT be displayed above the upload input
- **AND** the upload form SHALL render as it currently does
