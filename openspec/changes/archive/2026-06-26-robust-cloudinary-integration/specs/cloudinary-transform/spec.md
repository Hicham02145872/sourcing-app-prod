## ADDED Requirements

### Requirement: Transformable image URLs via media_url()

The `media_url()` helper SHALL accept an optional second parameter `$transformations` as an associative array of Cloudinary transformation parameters.

The following transformation parameters SHALL be supported:
- `width` (int) — width in pixels
- `height` (int) — height in pixels
- `crop` (string) — Cloudinary crop mode (e.g., `fill`, `fit`, `thumb`, `scale`)
- `quality` (string) — Cloudinary quality (e.g., `auto`, `auto:best`, `80`)
- `fetch_format` (string) — output format (e.g., `auto`, `webp`, `jpg`)

#### Scenario: Transform a Cloudinary URL
- **WHEN** `media_url($cloudinaryUrl, ['width' => 300, 'height' => 300, 'crop' => 'fill'])` is called
- **THEN** the returned URL SHALL include Cloudinary transformation segments `/w_300,h_300,c_fill/`
- **AND** the original asset on Cloudinary SHALL NOT be modified

#### Scenario: No transformation for local paths
- **WHEN** `media_url($localPath, ['width' => 300])` is called with a local storage path
- **THEN** the returned URL SHALL be the plain `asset('storage/' . $path)` without any transformations

#### Scenario: Empty transformations returns original URL
- **WHEN** `media_url($cloudinaryUrl, [])` or `media_url($cloudinaryUrl)` is called
- **THEN** the returned URL SHALL be the original `secure_url` unchanged

#### Scenario: Null or empty path returns empty string
- **WHEN** `media_url(null)` or `media_url('')` is called with any transformations
- **THEN** an empty string SHALL be returned

### Requirement: Default quality transformation

The system SHALL apply `f_auto,q_auto` by default to all Cloudinary URLs generated through `media_url()` unless overridden.

#### Scenario: Default transformations applied
- **WHEN** `media_url($cloudinaryUrl)` is called without explicit transformations
- **THEN** the URL SHALL include `/f_auto,q_auto/` by default

## REMOVED Requirements

<!-- No requirements removed -->

## MODIFIED Requirements

<!-- No existing requirements to modify -->
