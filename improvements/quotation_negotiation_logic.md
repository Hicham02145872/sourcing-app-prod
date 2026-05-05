# Quotation Negotiation & Definitive Rejection Logic

This document outlines the implementation of a negotiation flow for quotations, allowing clients to request changes instead of simply rejecting a proposal.

## Proposed Changes

### 1. Database & Models
- Add `negotiating` status to `SourcingRequest` statuses.
- Add `negotiating` status to `Quotation` statuses.
- (Optional) Add a `negotiation_notes` field to the `quotations` table to store client feedback.

### 2. Models Update
- `App\Models\SourcingRequest`: Update `STATUSES` and `canTransitionTo`.
- `App\Models\Quotation`: Update `STATUSES`.

### 3. Controller Updates
- `App\Http\Controllers\Client\QuotationController`: Add `negotiate` method.
- Update `reject` method if needed.

### 4. View Updates
- `resources/views/client/sourcing-requests/show.blade.php`: Add "Negotiate" button/modal and "Definitive Reject" option.

## Workflow
1. Client clicks "Negotiate".
2. Client provides reason/target price in a modal.
3. Status changes to `negotiating`.
4. Admin is notified.
5. Admin updates quotation and status changes back to `quoted`.
