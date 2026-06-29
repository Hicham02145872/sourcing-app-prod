## ADDED Requirements

### Requirement: Admin can view real product photos in full size
The admin quotation interface SHALL provide a click-to-view feature that displays real product photos in full size within a modal/lightbox.

#### Scenario: Admin clicks a real photo to view full size
- **WHEN** an admin clicks on a real product image (`real_product_image` or `quotation_media` image) in the quotation edit or show view
- **THEN** a modal overlay SHALL open displaying the image at its full resolution via `media_url()`
- **AND** the modal SHALL have a close button and support closing via Escape key or clicking outside the image

#### Scenario: Video media is not affected by viewer
- **WHEN** an admin clicks on a video media item
- **THEN** the existing video player SHALL continue to function (no photo viewer modal for videos)

### Requirement: Admin can selectively delete individual real photos
The admin quotation interface SHALL provide a standalone "Delete" button per real photo that immediately removes the image from the server and database.

#### Scenario: Admin deletes a specific real photo
- **WHEN** an admin clicks the "Delete" button on a specific real photo (`real_product_image` or individual `quotation_media` item)
- **THEN** a confirmation dialog SHALL appear asking to confirm deletion
- **WHEN** the admin confirms deletion
- **THEN** the file SHALL be deleted from storage (local or Cloudinary)
- **AND** the database record SHALL be removed
- **AND** the UI SHALL immediately update to remove the deleted photo from the gallery

#### Scenario: Deleting the featured photo resets the field
- **WHEN** an admin deletes the `real_product_image` (featured photo)
- **THEN** the `quotations.real_product_image` field SHALL be set to NULL
- **AND** the featured photo preview area SHALL show a "No image" placeholder

### Requirement: Delete functionality replaces checkbox batch delete
The per-photo standalone delete SHALL replace the current checkbox-based batch delete mechanism for quotation media.

#### Scenario: Per-photo delete buttons are shown instead of checkboxes
- **WHEN** an admin views the existing media section in quotation edit
- **THEN** each media item SHALL display a standalone "Delete" button instead of a checkbox
- **AND** the form-level `delete_media[]` checkbox mechanism SHALL be removed
