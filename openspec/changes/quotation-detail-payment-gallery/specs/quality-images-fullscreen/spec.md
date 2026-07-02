# quality-images-fullscreen Specification

## Purpose
Allow clients to click on quality option images to view them in full-screen mode for better visualization.

## Requirements

### Requirement: Client can click quality option image to view full screen
Each quality option image (`low`, `medium`, `good`) in the client sourcing request detail page SHALL be clickable and open in a full-screen modal when clicked.

#### Scenario: Client clicks on quality option image
- **WHEN** a client clicks on a quality option image in the sourcing request detail page
- **THEN** the image SHALL open in a full-screen modal overlay
- **AND** the modal SHALL show the image at full resolution
- **AND** the modal SHALL have a close button
- **AND** clicking outside the image SHALL close the modal

#### Scenario: Quality option has no image
- **WHEN** a quality option has no `image_path` set
- **THEN** the placeholder icon SHALL NOT be clickable
