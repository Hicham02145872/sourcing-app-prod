## ADDED Requirements

### Requirement: Delete Cloudinary asset on model deletion

The system SHALL delete the corresponding Cloudinary asset when a model with a `cloudinary_public_id` is deleted.

The deletion SHALL be dispatched as a queued job `DeleteCloudinaryAsset` to avoid slowing the HTTP response.

The following models SHALL have Cloudinary cleanup on delete:
- `SourcingRequest` (field: `product_image`)
- `Quotation` (fields: `real_product_image`, `quality_options[*].image_path`)
- `QuotationMedia` (field: `file_path`)
- `SourcingOrder` (fields: `proof_of_payment_path`, `refund_proof_path`)
- `SourcingOrderMedia` (field: `file_path`)
- `PaymentMethod` (field: `logo_path`)
- `User` (field: `profile_photo_path`)
- `RefundRequest` (fields: `refund_proof_path`, `evidence_paths[*]`)

#### Scenario: Single asset deletion on model delete
- **WHEN** a model with a `cloudinary_public_id` is deleted
- **THEN** a `DeleteCloudinaryAsset` job SHALL be dispatched with the Public ID
- **AND** the existing local file cleanup SHALL remain in place

#### Scenario: Multiple asset deletion (JSON array fields)
- **WHEN** a `RefundRequest` with multiple `evidence_paths` is deleted
- **THEN** a `DeleteCloudinaryAsset` job SHALL be dispatched for each Public ID

### Requirement: Retry on Cloudinary deletion failure

The `DeleteCloudinaryAsset` job SHALL retry up to 3 times with exponential backoff (30s, 2min, 5min) if the Cloudinary API returns an error.

#### Scenario: Retry on network failure
- **WHEN** the Cloudinary API is unreachable
- **THEN** the job SHALL be re-queued with backoff delay
- **AND** after 3 failed attempts, the job SHALL log a critical error and fail permanently

### Requirement: Graceful fallback if Cloudinary not configured

If `CLOUDINARY_URL` is not set, the deletion job SHALL complete silently without error.

#### Scenario: Cloudinary not configured
- **WHEN** `CLOUDINARY_URL` is empty
- **THEN** the `DeleteCloudinaryAsset` job SHALL do nothing and mark itself as complete

### Requirement: PaymentMethodController uses ImageProcessingService

The `PaymentMethodController` SHALL use `ImageProcessingService::compressAndStore()` for logo uploads instead of `Storage::disk('public')->store()`.

#### Scenario: Payment method logo upload with Cloudinary
- **WHEN** an admin uploads a payment method logo
- **THEN** the controller SHALL call `ImageProcessingService::compressAndStore()`
- **AND** the logo SHALL be uploaded to Cloudinary (if configured) with fallback to local

#### Scenario: Delete old logo on update
- **WHEN** a payment method logo is updated
- **THEN** the old logo SHALL be deleted from Cloudinary (if it exists there) and from local disk

#### Scenario: Delete logo on payment method deletion
- **WHEN** a payment method is deleted
- **THEN** the logo SHALL be deleted from Cloudinary and local disk via the model event

## REMOVED Requirements

<!-- No requirements removed -->

## MODIFIED Requirements

<!-- No existing requirements to modify -->
