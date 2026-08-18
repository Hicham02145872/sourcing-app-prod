## Why

On the client sourcing request detail page, when the admin uploads a video as quality option media, the client sees a broken image instead of the video. The quality options selector and the media modal only render `<img>` tags — they have no `<video>` support. Images work fine, but videos fail silently.

## What Changes

- **Quality options selector**: Add video detection and `<video>` rendering for quality option thumbnails. When `image_path` points to a video file (by extension), render a `<video>` tag with controls instead of an `<img>` tag.
- **Media modal**: Upgrade the existing image-only modal to also support video playback. Add a `<video>` element and toggle logic so the modal can open both images and full-size videos, matching the working implementation from the client sourcing orders page.

## Capabilities

### New Capabilities
- `client-request-video-display`: Video rendering in quality option thumbnails and full-size media modal on the client sourcing request detail page.

### Modified Capabilities

(none — no existing specs cover this page)

## Impact

- `resources/views/client/sourcing-requests/show.blade.php` — quality options preview section (lines 565-575) and media modal + JS (lines 842-871).
- No backend changes needed — the data already stores video file paths and the `QuotationMedia` model already distinguishes image vs video.
- No breaking changes — existing image rendering stays identical; video support is additive.
