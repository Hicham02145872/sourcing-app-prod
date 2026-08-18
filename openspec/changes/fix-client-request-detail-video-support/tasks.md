## 1. Quality Options Thumbnail Video Support

- [x] 1.1 Add video extension detection helper in the quality options Blade section (lines 565-575 of `show.blade.php`) using `pathinfo($opt['image_path'], PATHINFO_EXTENSION)`
- [x] 1.2 Replace the `<img>` tag with conditional rendering: `<video>` for video extensions (mp4, mov, avi, webm) with `controls`, `preload="metadata"`, and `class="w-full h-full object-cover"`; `<img>` for images (existing behavior)
- [x] 1.3 Add a play icon overlay (triangle SVG) on video thumbnails

## 2. Media Modal Video Support

- [x] 2.1 Add `<video id="modalVideo">` element alongside the existing `<img id="modalImage">` in the modal markup (lines 842-852)
- [x] 2.2 Update `openMediaModal(src, type)` JS function to toggle between image and video elements (show/hide, set src, pause on switch)
- [x] 2.3 Update `closeMediaModal()` to pause and clear video source when closing
- [x] 2.4 Add Escape key handler to also pause video on close

## 3. Verification

- [ ] 3.1 Test with a quality option that has a video as `image_path` — verify thumbnail shows `<video>` with controls and play icon
- [ ] 3.2 Test clicking video thumbnail opens modal with video player
- [ ] 3.3 Test clicking image in verification gallery still opens modal with image
- [ ] 3.4 Test closing modal pauses any playing video
- [ ] 3.5 Test Escape key closes modal for both image and video
