## ADDED Requirements

### Requirement: Migrate local images to Cloudinary

The system SHALL provide an Artisan command to migrate all existing locally-stored images to Cloudinary.

The command SHALL cover the following models and fields:
- `SourcingRequest.product_image`
- `Quotation.real_product_image`
- `Quotation.quality_options[*].image_path`
- `QuotationMedia.file_path`
- `SourcingOrder.proof_of_payment_path`
- `SourcingOrder.refund_proof_path`
- `SourcingOrderMedia.file_path`
- `PaymentMethod.logo_path`
- `User.profile_photo_path`
- `RefundRequest.refund_proof_path`
- `RefundRequest.evidence_paths[*]`

The system SHALL skip records where the field already starts with `http://` or `https://`.

#### Scenario: Successful single image migration
- **WHEN** a local image path exists and is accessible on the `public` disk
- **THEN** the image SHALL be uploaded to Cloudinary
- **AND** the model field SHALL be updated to the Cloudinary `secure_url`
- **AND** the `cloudinary_public_id` field SHALL store the uploaded asset's Public ID

#### Scenario: Non-existent local file
- **WHEN** the local file does not exist on disk
- **THEN** the command SHALL output a warning and skip that record
- **AND** continue processing remaining records

#### Scenario: Cloudinary upload failure
- **WHEN** the Cloudinary API returns an error
- **THEN** the command SHALL log the error with model type and ID
- **AND** continue processing remaining records
- **AND** NOT modify the record's path

### Requirement: Dry-run mode

The command SHALL support a `--dry-run` flag that reports what would be migrated without executing any uploads or database updates.

#### Scenario: Dry-run lists affected records
- **WHEN** the command is run with `--dry-run`
- **THEN** the system SHALL output a summary of records per model type
- **AND** SHALL NOT call the Cloudinary API
- **AND** SHALL NOT modify any database records
- **AND** SHALL output the total count of images to migrate

### Requirement: Batch processing

The command SHALL support `--batch-size` (default: 50) and `--delay` (default: 0 seconds) options to control rate of migration.

#### Scenario: Batch processing with delay
- **WHEN** `--batch-size=20 --delay=1` is specified
- **THEN** the command SHALL process records in batches of 20
- **AND** wait 1 second between batches

### Requirement: Migration rollback

The command SHALL generate a migration log file as JSON at `storage/logs/cloudinary-migration-{timestamp}.json` with original local paths, Cloudinary URLs, and Public IDs.

The command SHALL support a `--rollback` flag that restores local paths from the last migration log.

#### Scenario: Rollback restores original paths
- **WHEN** `--rollback` is specified
- **THEN** the system SHALL read the latest migration log file
- **AND** restore each model field to its original local path
- **AND** clear the `cloudinary_public_id` field
- **AND** delete the uploaded Cloudinary asset

## REMOVED Requirements

<!-- No requirements removed -->

## MODIFIED Requirements

<!-- No existing requirements to modify -->
