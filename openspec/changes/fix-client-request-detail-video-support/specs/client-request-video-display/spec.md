## ADDED Requirements

### Requirement: Quality option thumbnail renders video correctly
When a quality option's `image_path` points to a video file (mp4, mov, avi, or webm), the system SHALL render a `<video>` element with controls instead of an `<img>` element.

#### Scenario: Video quality option thumbnail
- **WHEN** a quality option has `image_path` ending in `.mp4`, `.mov`, `.avi`, or `.webm`
- **THEN** the thumbnail area SHALL display a `<video>` tag with `controls` and `preload="metadata"` attributes
- **AND** a play icon or VIDEO badge SHALL be overlaid on the thumbnail

#### Scenario: Image quality option thumbnail
- **WHEN** a quality option has `image_path` ending in a non-video extension (e.g., `.jpg`, `.png`)
- **THEN** the thumbnail area SHALL display an `<img>` tag as it does today

#### Scenario: Click on quality option thumbnail opens modal
- **WHEN** user clicks on a video quality option thumbnail
- **THEN** the media modal SHALL open and play the video in full size

### Requirement: Media modal supports both images and videos
The full-size media modal SHALL display both images and videos with appropriate rendering elements.

#### Scenario: Open image in modal
- **WHEN** user clicks an image in the Product Quality Verification gallery
- **THEN** the modal SHALL display the image in full size with close button

#### Scenario: Open video in modal
- **WHEN** user clicks a video thumbnail (quality option or verification gallery)
- **THEN** the modal SHALL display the video with playback controls in full size
- **AND** the modal SHALL hide the image element and show the video element

#### Scenario: Close modal stops video
- **WHEN** user closes the modal while a video is playing
- **THEN** the video SHALL be paused and its source cleared

#### Scenario: Escape key closes modal
- **WHEN** user presses Escape while the modal is open
- **THEN** the modal SHALL close (for both image and video)
