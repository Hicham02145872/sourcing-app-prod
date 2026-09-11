## ADDED Requirements

### Requirement: Admin can mark a request as in transit from China
The system SHALL allow an admin (or super admin) to move a sourcing request to the `in_transit_china` status, provided the transition is allowed by the existing transition map, and SHALL record a China tracking number and a package label photo on the linked sourcing order.

#### Scenario: Admin marks a request as in transit with photo and tracking
- **WHEN** an admin with permission opens the workflow for a request in `accepted` status
- **AND** submits an in-transit action with a valid image (jpg/png/webp, max 5 MB) and a non-empty `china_tracking_number`
- **THEN** the request status becomes `in_transit_china`
- **AND** the linked order's `china_tracking_number` and `package_label_photo_path` are updated
- **AND** the order status is set to `in_transit_china` only when its own validity rules allow it

#### Scenario: Invalid photo is rejected
- **WHEN** an admin submits the in-transit action with a file over 5 MB or of an unsupported format
- **THEN** the action is rejected with a validation error
- **AND** no status change occurs

#### Scenario: Unauthorized transition is refused
- **WHEN** the current request status does not allow moving to `in_transit_china`
- **THEN** the action is rejected
- **AND** the request status is unchanged

### Requirement: Client sees the in-transit phase, super admin sees the internal details
The system SHALL display the `in_transit_china` phase to the client on their request as a translated status only. The China tracking number (`china_tracking_number`) and the package photo (`package_label_photo_path`) SHALL be internal and visible exclusively to super admins in admin views; they SHALL NOT be exposed on any client-facing view.

#### Scenario: Client sees the phase without the internal details
- **WHEN** a request is in `in_transit_china`
- **AND** the linked order has a China tracking number and photo
- **THEN** the client request detail shows the translated status
- **AND** the China tracking number and the package photo are not rendered on the client view

#### Scenario: Super admin sees the internal details
- **WHEN** a request is in `in_transit_china`
- **AND** a super admin opens the request or order in admin views
- **THEN** the China tracking number and the package photo are displayed

#### Scenario: No photo yet, phase still displayed
- **WHEN** a request is in `in_transit_china` but no package photo is stored yet
- **THEN** the phase is still displayed in both client and super admin views
- **AND** the super admin view shows the tracking number without an image placeholder error