## ADDED Requirements

### Requirement: Upload with Cloudinary primary and local fallback
The system SHALL attempt to upload media files to Cloudinary first. If Cloudinary is unreachable, misconfigured, or returns an error, the system SHALL fall back to the local `public/` disk without breaking the request.

#### Scenario: Cloudinary configured and reachable
- **WHEN** `CLOUDINARY_URL` is set and Cloudinary API responds successfully
- **THEN** the file SHALL be uploaded to Cloudinary and the local `public/` disk
- **THEN** the returned `ImageResult` SHALL have a non-null `publicId` and a `path` pointing to the local file

#### Scenario: Cloudinary configured but unreachable
- **WHEN** `CLOUDINARY_URL` is set but the Cloudinary API returns a connection/timeout/auth error
- **THEN** the file SHALL be stored only on the local `public/` disk
- **THEN** the error SHALL be logged with level `warning`
- **THEN** the returned `ImageResult` SHALL have a null `publicId`
- **THEN** the request SHALL complete successfully (no exception thrown)

#### Scenario: Cloudinary not configured
- **WHEN** `CLOUDINARY_URL` is empty or not set
- **THEN** the file SHALL be stored only on the local `public/` disk
- **THEN** the returned `ImageResult` SHALL have a null `publicId`
- **THEN** no Cloudinary SDK call SHALL be attempted

#### Scenario: Non-image file upload
- **WHEN** the uploaded file is not an image (e.g., PDF, DOCX)
- **THEN** the file SHALL be stored only on the local `public/` disk regardless of Cloudinary configuration
- **THEN** the returned `ImageResult` SHALL have a null `publicId`

### Requirement: DeleteCloudinaryAsset job handles missing config gracefully
The `DeleteCloudinaryAsset` job SHALL exist and SHALL skip deletion without error when `CLOUDINARY_URL` is empty or when the public ID is null/empty.

#### Scenario: Cloudinary configured and public ID provided
- **WHEN** `CLOUDINARY_URL` is set and the job receives a valid public ID
- **THEN** the job SHALL call Cloudinary's destroy API for that public ID

#### Scenario: Cloudinary not configured
- **WHEN** `CLOUDINARY_URL` is empty or not set
- **THEN** the job SHALL return without attempting any API call
- **THEN** a debug-level log entry SHALL be written

#### Scenario: Null or empty public ID
- **WHEN** the job receives a null or empty public ID
- **THEN** the job SHALL return without attempting any API call

### Requirement: Database stores cloudinary_public_id
The system SHALL store the Cloudinary public ID as a nullable string column on all 8 media-related tables.

#### Scenario: Column exists and is nullable
- **WHEN** inspecting the database schema for each affected table
- **THEN** a `cloudinary_public_id` column SHALL exist
- **THEN** the column SHALL be nullable (`NULL` by default)
- **THEN** the column SHALL be of type `string` (VARCHAR)

#### Scenario: Model fillable includes cloudinary_public_id
- **WHEN** saving a model with a `cloudinary_public_id` value
- **THEN** the value SHALL persist to the database
- **THEN** reading the model back SHALL return the same value via the same attribute
