## Context

The client sourcing request detail page (`resources/views/client/sourcing-requests/show.blade.php`) displays quality options with a thumbnail preview and a full-size media modal. Currently both only handle images — the quality options section always renders `<img>` and the modal only has an `<img>` element.

This contrasts with:
- The **Product Quality Verification** gallery (same file, lines 365-396) which already correctly uses `isImage()`/`isVideo()` checks and renders `<video>` for videos.
- The **client sourcing orders** page (`sourcing-orders/show.blade.php`, lines 678-728) which has a fully working image+video modal.

The admin already uploads videos to quality options (stored as file paths in `quality_options[image_path]` and in the `quotation_media` table). The data model is ready — only the Blade rendering is missing.

## Goals / Non-Goals

**Goals:**
- Quality option thumbnails render `<video>` with controls when the file is a video (detected by extension).
- The media modal opens and plays both images and full-size videos.
- Match the existing working patterns from the orders page modal.

**Non-Goals:**
- No backend changes — data model and upload handling already work.
- No changes to the Product Quality Verification gallery — it already handles videos correctly.
- No video compression or transcoding — videos are stored and served as-is.

## Decisions

### 1. Detect video by file extension, not MIME type
Use `pathinfo($path, PATHINFO_EXTENSION)` to check for `mp4`, `mov`, `avi`, `webm`. This matches the pattern used in `refund-requests/show.blade.php` and avoids needing to pass MIME types from the backend.

**Alternative considered:** Pass `file_type` from `QuotationMedia` model via PHP — not possible for quality options since they store paths in a JSON column, not in the media table.

### 2. Reuse the orders page modal pattern exactly
Copy the `<video id="modalImage">` + `<video id="modalVideo">` dual-element modal with toggle logic from `sourcing-orders/show.blade.php`. This is already proven code and keeps the UI consistent.

**Alternative considered:** Use a third-party lightbox library — adds unnecessary dependency for a simple fix.

### 3. Video thumbnail with play icon overlay
For quality option thumbnails showing videos, add a play icon overlay (triangle SVG) on top of the `<video>` element to indicate it's a video, matching the VIDEO badge pattern from the verification gallery.

## Risks / Trade-offs

- **Large video files may be slow to load inline** → Mitigated by `preload="metadata"` attribute, which only loads enough to show dimensions.
- **Mobile autoplay policies** → Not relevant since we don't auto-play; user must click play.
- **Extension-based detection could misidentify files** → Low risk since uploads are validated server-side to only accept `mp4`, `mov`, `avi`, `webm`.
