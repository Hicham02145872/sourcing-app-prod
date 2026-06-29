## ADDED Requirements

### Requirement: No file count limit on sourcing order media uploads
The admin sourcing order media upload endpoint SHALL accept an unlimited number of files per request (subject to server configuration).

#### Scenario: Admin uploads more than 10 files to a sourcing order
- **WHEN** an admin selects more than 10 files for upload on a sourcing order
- **THEN** all files SHALL be accepted and processed
- **AND** no `max:N` file count validation SHALL reject the upload
- **AND** per-file size and mime type validation SHALL still apply

### Requirement: UI reflects unlimited attachment capability
The file upload interface SHALL not display any file count limit message to the user.

#### Scenario: Upload UI does not show a count limit
- **WHEN** an admin views the media upload section for a sourcing order or quotation
- **THEN** no message indicating a maximum number of files SHALL be displayed
- **AND** the file input SHALL accept multiple files without restriction

### Requirement: Existing per-file limits remain unchanged
The removal of file count limits SHALL NOT affect per-file size limits or accepted mime types.

#### Scenario: Per-file validation still applies
- **WHEN** an admin uploads a file exceeding the per-file size limit
- **THEN** the upload SHALL be rejected with the existing size validation error
- **WHEN** an admin uploads a file with an unsupported mime type
- **THEN** the upload SHALL be rejected with the existing mime type validation error
