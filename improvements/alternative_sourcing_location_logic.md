# Logic: Alternative Sourcing Location (e.g., Requested China -> Found in Dubai)

**Scenario**:
The Client submits a `SourcingRequest` specifying "China" as the sourcing location.
The Admin investigates and discovers the product is better sourced from "Dubai" (United Arab Emirates) due to availability, price, or logistics.

---

## 1. Workflow Overview

### Step 1: Admin Updates Sourcing Request
Before creating a quotation, the Admin needs to update the "Truth" of the request.
*   **Action**: Admin opens the Sourcing Request.
*   **Update**: Admin changes `sourcing_location` from **China** to **Dubai**.
*   **Reasoning Field** (Optional): Admin can add a note explaining why (e.g., "Product unavailable in China", "Better pricing in Dubai").

### Step 2: Quotation Creation
When the Admin creates the `Quotation`:
*   The system must use "Dubai" as the **Origin** for shipping calculations.
*   The field currently named `delivery_cost_china` (representing domestic shipping to warehouse) will implicitly be treated as **"Domestic Shipping (Dubai)"**.
    *   *Recommendation*: Rename `delivery_cost_china` to `domestic_shipping_cost` in the database to allow for multi-country sourcing without confusion.

### Step 3: Client Notification & Review
*   **Notification**: The Client receives a notification: *"Your Sourcing Request #123 has been updated. Origin changed to Dubai."* OR this is communicated via the Quotation note.
*   **Quotation View**: The Client sees that the product will be shipped from **Dubai**.
    *   Shipping method (Air/Sea) availability might change based on the new origin.

---

## 2. Technical Requirements

### 2.1. Database Changes
*   **Refactor Needed**: The `quotations` table currently uses `delivery_cost_china`.
    *   **Action**: Create a migration to rename `delivery_cost_china` to `origin_shipping_cost` or `domestic_shipping_cost`.
    *   **Impact**: Update all references in `Quotation` model and calculations.

### 2.2. Admin Interface
*   **Edit Capability**: Ensure the `SourcingRequest` edit form allows changing the `sourcing_location`.
*   **Quotation Form**: Label the domestic shipping field dynamically based on the Request's location (e.g., if Location=Dubai, label="Delivery Cost (Dubai)").

### 2.3. Shipping Logic
*   **Shipping Rates**: Ensure the system has (or acts upon) shipping rates from **Dubai** to the destination, not just China-based rates.
    *   If automatic shipping calculation is used, it must accept "Origin Country" as a parameter.

---

## 3. Implementation Checklist

- [ ] **Backend**: Allow Admin to update `sourcing_location` on `SourcingRequest`.
- [ ] **Database**: Rename `delivery_cost_china` -> `domestic_shipping_cost` (Migration).
- [ ] **Frontend**: Update Quotation creation form to show "Domestic Shipping Cost" instead of "Delivery Cost China".
- [ ] **Frontend**: Display the actual Sourcing Location prominently on the Client's Quotation view.
