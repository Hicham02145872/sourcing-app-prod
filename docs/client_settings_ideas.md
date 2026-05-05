# Client Settings - Feature Ideas

> **Document Purpose**: This document outlines comprehensive settings features for the client-side of the FastSourcingBrothers application. These settings will enhance user experience, provide personalization options, and give clients control over their account and notifications.

---

## 1. Account Settings

### 1.1 Profile Information
- **Full Name**: Allow clients to update their display name
- **Email Address**: Update email with verification process
- **Phone Number**: Update contact phone with optional verification
- **Profile Photo**: Upload and manage profile picture
- **Company Information** (Optional):
  - Company name
  - Company registration number
  - Tax ID / VAT number
  - Industry / Business type

### 1.2 Password & Security
- **Change Password**: Secure password update with current password verification
- **Two-Factor Authentication (2FA)**: Enable/disable 2FA via:
  - SMS
  - Email
  - Authenticator app (Google Authenticator, Authy)
- **Active Sessions**: View and manage active login sessions
  - See device type, location, last active time
  - Ability to remotely logout from specific devices
- **Login History**: View recent login attempts and activity log

---

## 2. Notification Preferences

### 2.1 Push Notifications (FCM)
Client can enable/disable push notifications for:
- **Sourcing Requests**:
  - ✓ Request status changed
  - ✓ New quotation received
  - ✓ Request rejected/needs clarification
- **Quotations**:
  - ✓ New quotation available
  - ✓ Quotation accepted/rejected by admin
  - ✓ Price changes or updates
- **Orders**:
  - ✓ Order confirmed
  - ✓ Payment received
  - ✓ Order shipped
  - ✓ Tracking number added
  - ✓ Delivery status updates
- **Refunds**:
  - ✓ Refund request approved
  - ✓ Refund request rejected
  - ✓ Refund processed
- **General**:
  - ✓ Account updates
  - ✓ System announcements
  - ✓ Promotional offers
  - ✓ Newsletter

### 2.2 Email Notifications
Separate controls for email notifications:
- Daily/Weekly digest of activity
- Instant notifications for critical updates
- Marketing emails
- Order receipts and invoices

### 2.3 SMS Notifications (Optional)
- Order status updates
- Delivery notifications
- Important account alerts

### 2.4 In-App Notifications
- Badge count preferences
- Notification sound
- Do Not Disturb schedule (e.g., 10 PM - 8 AM)

---

## 3. Display & Appearance

### 3.1 Language Preferences
- **Primary Language**: English, French, Arabic, Spanish, etc.
- **Auto-detect browser language**
- **Date/Time format**: Regional preferences (MM/DD/YYYY vs DD/MM/YYYY)

### 3.2 Theme & Accessibility
- **Theme Mode**:
  - Light mode
  - Dark mode
  - Auto (follow system preference)
- **Font Size**: Small, Medium, Large, Extra Large
- **Color Contrast**: Normal, High Contrast
- **Reduce Animations**: For accessibility

### 3.3 Currency & Units
- **Preferred Currency**: USD, EUR, MAD, GBP, etc.
  - Automatic conversion display
  - Default currency for quotes
- **Weight/Dimension Units**: 
  - Metric (kg, cm)
  - Imperial (lbs, inches)

---

## 4. Order & Shipping Preferences

### 4.1 Saved Addresses
- **Shipping Addresses**: 
  - Add multiple addresses
  - Set default shipping address
  - Label addresses (Home, Office, Warehouse, etc.)
  - Quick address selector during checkout
- **Billing Addresses**:
  - Separate billing address option
  - Same as shipping checkbox

### 4.2 Default Shipping Preferences
- **Preferred Carrier**: DHL, FedEx, UPS, etc.
- **Preferred Shipping Speed**: Standard, Express, Priority
- **Special Instructions**: Default delivery notes

### 4.3 Payment Preferences
- **Saved Payment Methods**:
  - Credit/Debit cards (tokenized)
  - Bank account details
  - PayPal/Digital wallets
- **Default Payment Method**: Quick checkout option
- **Payment Receipts**: Auto-download or email

---

## 5. Communication Preferences

### 5.1 Preferred Contact Method
- Email (primary)
- Phone call
- SMS
- WhatsApp
- In-app messages

### 5.2 Business Hours for Contact
- Set preferred hours for receiving calls
- Timezone selection
- Out-of-office status

### 5.3 Admin/Sales Representative Preference
- Request to be primarily handled by a specific admin
- Language preference for support

---

## 6. Privacy & Data Management

### 6.1 Privacy Controls
- **Data Visibility**:
  - Make profile public/private
  - Show/hide order history to admins
- **Data Sharing**:
  - Allow anonymous usage analytics
  - Marketing preferences
  - Third-party data sharing opt-out

### 6.2 Data Export & Download
- **Download My Data**: Export all account data in JSON/PDF format
  - Order history
  - Quotations
  - Communications
  - Personal information

### 6.3 Account Deletion
- **Request Account Deletion**:
  - Soft delete with 30-day grace period
  - Permanent delete option
  - Export data before deletion
  - Clear explanation of consequences

---

## 7. Sourcing Request Defaults

### 7.1 Template Preferences
- **Saved Request Templates**:
  - Create templates for frequently ordered products
  - Quick-fill common fields
  - Category defaults
- **Auto-fill Options**:
  - Remember last used service type
  - Default quantity ranges
  - Preferred product categories

### 7.2 Attachment Preferences
- **Max File Size Notification**: Alert when files are too large
- **Auto-compress Images**: Enable/disable automatic image compression
- **Preferred File Format**: PDF, Images, Documents

---

## 8. Reporting & Analytics (Optional)

### 8.1 Personal Dashboard Customization
- **Widget Management**:
  - Show/hide dashboard widgets
  - Customize widget order
  - Add custom KPIs
- **Default Dashboard View**:
  - Overview
  - Recent orders
  - Pending requests
  - Tracking

### 8.2 Export Preferences
- **Auto-generate Reports**:
  - Monthly order summary
  - Spending analytics
  - Supplier performance
- **Report Format**: PDF, Excel, CSV

---

## 9. Integration Settings

### 9.1 API Access (For Advanced Users)
- **API Keys**: Generate and manage API keys
  - Read-only access
  - Full access
  - Webhook endpoints
- **Developer Mode**: Enable advanced features

### 9.2 Third-Party Integrations
- **Google Sheets**: Auto-sync order data
- **Accounting Software**: QuickBooks, Xero integration
- **CRM**: HubSpot, Salesforce connections
- **Calendar**: Sync delivery dates with Google Calendar/Outlook

---

## 10. Help & Support Settings

### 10.1 Support Preferences
- **Preferred Support Channel**:
  - Live chat
  - Email tickets
  - Phone support
  - WhatsApp
- **Support Language**: Preferred language for support

### 10.2 Interactive Tutorials
- **Show Tutorial Tooltips**: Enable/disable hints for new features
- **Tutorial Progress**: Reset tutorial status
- **Quick Help Access**: Enable floating help button

---

## Implementation Priority

### Phase 1 (MVP - Essential)
1. Account Settings (Profile, Password)
2. Notification Preferences (Push, Email)
3. Language & Currency
4. Saved Addresses

### Phase 2 (Enhanced UX)
1. Display & Appearance (Theme, Accessibility)
2. Communication Preferences
3. Sourcing Request Defaults
4. Privacy Controls

### Phase 3 (Advanced Features)
1. Payment Preferences
2. Data Export
3. Reporting & Analytics
4. API Access & Integrations

---

## Technical Considerations

### Database Tables Needed

#### `user_settings` table
```sql
- id
- user_id (FK)
- theme_mode (light/dark/auto)
- language (en/fr/ar/es)
- currency (USD/EUR/MAD)
- font_size (small/medium/large)
- timezone
- created_at
- updated_at
```

#### `user_notification_preferences` table
```sql
- id
- user_id (FK)
- channel (push/email/sms)
- event_type (order_confirmed, quotation_received, etc.)
- is_enabled (boolean)
- created_at
- updated_at
```

#### `user_addresses` table
```sql
- id
- user_id (FK)
- type (shipping/billing)
- label (Home/Office/etc.)
- address_line_1
- address_line_2
- city
- state
- postal_code
- country
- is_default (boolean)
- phone
- created_at
- updated_at
```

### Frontend Components
- Settings navigation (tabs/sidebar)
- Toggle switches for boolean settings
- Form validation
- Success/error messages
- Confirmation modals for destructive actions
- Real-time preview for theme changes

### Backend Requirements
- RESTful API endpoints for settings CRUD
- Validation rules for each setting type
- Event listeners for settings changes
- Cache user preferences for performance
- Audit log for security settings changes

---

## UI/UX Best Practices

1. **Progressive Disclosure**: Group related settings, don't overwhelm users
2. **Clear Labels**: Use plain language, avoid technical jargon
3. **Instant Feedback**: Show success messages and preview changes
4. **Search Functionality**: Allow users to search settings
5. **Reset to Default**: Provide option to reset all settings
6. **Mobile-First**: Ensure all settings are accessible on mobile
7. **Accessibility**: Support keyboard navigation and screen readers
8. **Confirmation Dialogs**: For destructive actions (delete account, disable 2FA)

---

## Security Considerations

- Password change requires current password
- Email/phone changes require verification
- 2FA changes send security alert emails
- Session management with CSRF protection
- Rate limiting on settings API endpoints
- Audit trail for critical settings changes
- Encrypt sensitive data (payment info, addresses)

---

## Testing Checklist

- [ ] All settings can be updated successfully
- [ ] Validation works for each field
- [ ] Changes persist after logout/login
- [ ] Notification preferences are respected
- [ ] Theme changes apply immediately
- [ ] Language changes affect entire app
- [ ] Currency conversion works correctly
- [ ] Address CRUD operations work
- [ ] 2FA enrollment process works
- [ ] Account deletion workflow functions properly
- [ ] Mobile responsive design
- [ ] Accessibility standards met
