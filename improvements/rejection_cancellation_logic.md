# Rejection and Cancellation Logic Specification

This document outlines the business logic and technical requirements for handling rejection and cancellation scenarios in the Sourcing App.

## 1. Client Actions

### 1.1. Client Cancels Sourcing Request
**Scenario**: A client decides to stop a sourcing request that is in progress.

*   **Pre-conditions**:
    *   `SourcingRequest` status is `pending`, `in_review`, or `quoted`.
    *   The user is the owner of the request.
*   **Trigger**:
    *   Client clicks the "Cancel Request" button on the request details page.
    *   Optional: Client provides a reason for cancellation.
*   **System Actions**:
    1.  **Update Status**:
        *   Set `SourcingRequest.status` to `cancelled`.
    2.  **Handle Associated Quotation**:
        *   If a `Quotation` exists and is `pending` or `sent`, set `Quotation.status` to `cancelled` (or `rejected`).
    3.  **Notifications**:
        *   **To Admin**: Send a notification to the **Assigned Admin** (if any) or Super Admins informing them the request has been cancelled by the client.
            *   *Channel*: Database (Notification Center) + Email.
            *   *Message*: "Sourcing Request #ID has been cancelled by the client."

### 1.2. Client Rejects Quotation
**Scenario**: A client receives a quotation but is not satisfied with the price or terms.

*   **Pre-conditions**:
    *   `SourcingRequest` status is `quoted`.
    *   `Quotation` status is `sent` (or `pending` visible to user).
*   **Trigger**:
    *   Client clicks "Reject Quotation" button.
    *   **Input Required**: Reason for rejection (e.g., "Price too high", "Too slow", "Found elsewhere").
*   **System Actions**:
    1.  **Update Status**:
        *   Set `Quotation.status` to `rejected`.
        *   Set `SourcingRequest.status` to `rejected`.
            *   *Note*: If the workflow allows re-quoting, the request might stay `quoted` or move to a `negotiation` state. However, based on current model transitions (`quoted` -> `rejected`), we move to `rejected`.
    2.  **Notifications**:
        *   **To Admin**: Send a notification to the **Assigned Admin**.
            *   *Channel*: Database + Email.
            *   *Message*: "Quotation for Request #ID was rejected. Reason: [Reason]."

---

## 2. Admin Actions

### 2.1. Admin Rejects Sourcing Request
**Scenario**: An admin determines the product cannot be sourced or the request is invalid.

*   **Pre-conditions**:
    *   `SourcingRequest` status is `pending` or `in_review`.
    *   User is an Admin or Super Admin.
*   **Trigger**:
    *   Admin clicks "Reject Request".
    *   **Input Required**: Reason for rejection (e.g., "Item illegal", "Out of stock", "Below MOQ").
*   **System Actions**:
    1.  **Update Status**:
        *   Set `SourcingRequest.status` to `rejected`.
    2.  **Notifications**:
        *   **To Client**: Send a notification to the **Client**.
            *   *Channel*: Database + Email.
            *   *Message*: "Your Sourcing Request #ID has been rejected. Reason: [Reason]."

            *   *Message*: "Your Sourcing Request #ID has been rejected. Reason: [Reason]."

## 3. History Page & Dashboard Filtering

### 3.1 Dashboard Logic
*   **Goal**: Keep the main dashboard focused on active requests.
*   **Rule**: Explicitly **exclude** requests with status `cancelled` or `rejected` from the main list.
*   **Counts**: "Total" count should reflect "Active" requests (or we can keep Total as global but list only active). Let's filter Total to be relevant to the view.

### 3.2 History (Archived) Page
*   **Route**: `/client/sourcing-requests/archived`
*   **View**: A dedicated list view for `cancelled` and `rejected` requests.
*   **Columns**: Same as dashboard, but "Actions" can be limited (e.g., only View/Delete).
*   **Access**: Link from Dashboard (e.g., "View Archived Requests").

- [ ] **Frontend (Client)**: Add "Cancel" and "Reject Quotation" buttons with confirmation modals (reason input).
- [ ] **Frontend (Admin)**: Add "Reject" button with reason input modal.
- [ ] **Backend (Controllers/Livewire)**:
    - [ ] Implement `cancel()` method for Client.
    - [ ] Implement `rejectQuotation()` method for Client.
    - [ ] Implement `reject()` method for Admin.
    - [ ] Ensure validation using `canTransitionTo`.
- [ ] **Notifications**:
    - [ ] Create `SourcingRequestCancelledNotification`.
    - [ ] Create `QuotationRejectedNotification`.
    - [ ] Create `SourcingRequestRejectedNotification`.
