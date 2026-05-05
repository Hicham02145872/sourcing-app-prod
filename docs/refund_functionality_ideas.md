# Refund Management System: Brainstorming & Ideas

This document outlines the possibilities for a robust refund (or "refond") system within the Sourcing App, moving beyond simple manual adjustments to a structured workflow.

## 1. Core Workflow Concepts

### For the Client
- **Request Button**: A "Request Refund" button on the Sourcing Order page (only available for certain statuses like `delivered` or `paid`).
- **Request Form**:
    - **Type**: Full or Partial.
    - **Reason**: Dropdown (Damaged product, Wrong item, Delay, etc.) + Text description.
    - **Amount**: If partial, the client suggests an amount.
    - **Evidence**: File upload for photos/videos of the issue.
- **Tracking**: Client can see the status of their request (`Pending`, `Under Review`, `Approved`, `Processed`, `Rejected`).

### For the Admin
- **Refund Dashboard**: A dedicated view to see all active refund requests.
- **Review System**:
    - View client's evidence and notes.
    - Internal chat or notes for the team.
    - **Approve/Reject**: With a reason for the client.
- **Execution**: 
    - Adjust `refund_amount` automatically on approval.






### Evidence & Inspection Sync
If a product was flagged during "Inspection Media" (Already exists in the app), the system could suggest a proactive refund or make it easier to link the refund request to the inspection photos.

---

## 3. Integration Possibilities

### Financial Reporting
- **Profit Impact**: Automatically deduct refunds from the `net_profit_or_loss` (partially done in `SourcingOrderObserver`).
- **Google Sheets Sync**: Send refund data to the "Accounting" sheet for external tracking.


## 4. Database Schema Possibilities

- **New Table**: `refund_requests`
    - `id`, `sourcing_order_id`, `user_id`, `status`
    - `amount_requested`, `amount_approved`
    - `reason`, `admin_notes`, `evidence_paths` (JSON)
- **Modifications**:
    - Statuses in `sourcing_orders`: `waiting_for_refund`, `refund_rejected`, etc.

---

> [!TIP]
> **Proactive Refunds**: Our team could use the "Inspection Photos" to notice a defect *before* the client even gets the product, and initiate a "Proactive Refund" or discount to improve customer trust.
