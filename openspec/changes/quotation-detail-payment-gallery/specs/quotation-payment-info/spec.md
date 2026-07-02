# quotation-payment-info Specification

## Purpose
Display active payment methods (PaymentMethod model) with their account details on the client sourcing request detail page, so the client knows how to send payment.

## Requirements

### Requirement: Active payment methods are displayed on sourcing request detail page
The sourcing request detail page SHALL display a "Payment Methods" section listing all active payment methods with their details.

#### Scenario: Client views sourcing request with active payment methods
- **WHEN** a client views a sourcing request detail page
- **AND** there are active payment methods (`PaymentMethod::where('is_active', true)->get()`)
- **THEN** the page SHALL display a "Available Payment Methods" section showing each payment method with:
  - Payment method name (e.g., "CIH Bank", "Wise Transfer", "PayPal")
  - Payment method logo (if `logo_path` exists)
  - Account details as key-value pairs from the `details` JSON field (e.g., Bank Name, Account Number, IBAN, SWIFT)
- **AND** the details SHALL be expandable/collapsible via an accordion interaction (click to toggle)

#### Scenario: No active payment methods exist
- **WHEN** a client views a sourcing request detail page
- **AND** there are no active payment methods
- **THEN** no payment methods section SHALL be displayed

### Requirement: Payment method details are expandable
Each payment method SHALL be displayed as a card/button that can be clicked to expand and show its account details.

#### Scenario: Client clicks on a payment method
- **WHEN** a client clicks on a payment method
- **THEN** the account details for that method SHALL be revealed with a transition animation
- **AND** clicking the same method again SHALL collapse the details
- **AND** only one method's details SHALL be expandable at a time
