## ADDED Requirements

### Requirement: Video file support in quotation file uploads
The quotation creation and edit forms SHALL accept video files (mp4, mov, avi) in addition to image files in all file upload fields.

#### Scenario: Admin uploads a video file
- **WHEN** an admin is on the quotation create or edit page
- **AND** selects a video file (mp4, mov, or avi) under 10MB in a file upload field
- **THEN** the file SHALL be accepted
- **AND** a video preview SHALL be displayed using an HTML5 video element

#### Scenario: Admin uploads a video exceeding size limit
- **WHEN** an admin selects a video file larger than 10MB
- **THEN** the form SHALL reject the file
- **AND** display an error toast message

### Requirement: Pre-submission file deletion
The quotation creation and edit forms SHALL allow admins to remove individually selected files from the file upload queue before submitting the form.

#### Scenario: Admin deletes a file before submission
- **WHEN** an admin has selected multiple files for upload
- **AND** clicks the delete (X) button on one of the file previews
- **THEN** that file SHALL be removed from the upload queue
- **AND** the remaining files SHALL stay intact
- **AND** the form SHALL NOT submit the deleted file

#### Scenario: Admin deletes all files
- **WHEN** an admin deletes all files from the upload queue
- **THEN** the upload area SHALL return to its initial empty state
- **AND** no files SHALL be submitted with the form

### Requirement: File previews for images and videos
The file preview area SHALL render image thumbnails for image files and a video player for video files.

#### Scenario: Image file preview
- **WHEN** an admin selects an image file
- **THEN** the system SHALL display a thumbnail preview using FileReader

#### Scenario: Video file preview
- **WHEN** an admin selects a video file
- **THEN** the system SHALL display a video player preview with playback controls
