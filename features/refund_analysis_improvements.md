# Analysis & Improvement Plan: Refund Management System

## Current Functionality Overview
The current system allows clients to request refunds for delivered orders and provides an administrative interface for review and resolution.

### Core Features
- **Client Side**: Request full/partial refunds, category-based reasoning, evidence attachment (images/videos), and status tracking.
- **Admin Side**: KPI dashboard (Pending/Approved/Total), filtering system, decision panel (Approve/Reject), and financial synchronization with the Sourcing Order.
- **Automation**: Basic auto-approval threshold logic and event-driven notifications.

---

## Proposed Improvements

### 1. Unified Sourcing Visibility
**Issue**: The refund request list only shows the Order ID.
**Improvement**: Display the **Product Name** and **Thumbnail** from the parent `SourcingRequest` in the admin list. This allows admins to immediately recognize the item without clicking.

### 2. Discussion & Communication Segment
**Issue**: Admins can add notes, but there is no direct channel with the client for the refund.
**Improvement**: Implement a "Claim Chat" or "Timeline Comments" section within the refund detail page.
- Allow clients to explain further.
- Allow admins to request more evidence.

### 3. Smart Financial Safeguards
**Issue**: Partial refunds can be complex if there are multiple requests.
**Improvement**:
- **Balance Indicator**: Show "Remaining Refundable Amount" on the Order detail page.
- **Refund History**: A dedicated tab on the Order page showing all past refund attempts (approved/rejected/pending).

### 4. Administrative Configuration UI
**Issue**: Auto-approval limits are currently hardcoded in config files.
**Improvement**: Create a **Refund Settings** page for Super Admins to:
- Adjust the auto-approval dollar limit.
- Toggle between "Automatic" and "Manual" review modes.
- Define custom "Reason Categories".

### 5. Advanced Search & Export
**Issue**: Finding a specific refund requires manual filtering.
**Improvement**:
- **Global Search**: Search by Client Name, Email, or Product Name.
- **Report Export**: Export filtered refund data to Excel (CSV) or PDF for accounting purposes.

### 6. Technical Optimizations
- **Image Compression**: Integrate the `ImageProcessingService` into the `RefundRequestController` store method to optimize evidence storage.
- **Bulk Actions**: Allow Super Admins to approve/reject multiple pending requests at once.

---

## Technical Roadmap

#### Level 1: Low-Hanging Fruit
- [ ] Add Product Name to `admin.refund-requests.index`.
- [ ] Implement `ImageProcessingService` for evidence uploads.
- [ ] Add "Remaining Balance" calculation to the Order view.

#### Level 2: Enhanced Workflow
- [ ] Add internal chat system for refund requests.
- [ ] Implement Excel/PDF export.

#### Level 3: System Control
- [ ] Create Settings UI for refund configurations.
- [ ] Implement Bulk Action checkboxes in the table.
