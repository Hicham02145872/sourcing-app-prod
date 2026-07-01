## Context

The app uses Laravel 12 with Livewire 3 + Alpine.js + Tailwind CSS. A feature flag system already exists (`FeatureFlag` model, `FeatureFlagService`, `DevDashboard` for management, Blade directives `@feature`, `@featureVisible`). The quotation system uses standard Blade forms with Alpine.js for interactivity. File uploads are handled server-side via `ImageProcessingService::compressAndStore()` and client-side via FileReader previews. Media files are managed through `QuotationMedia` model and `QuotationMediaController`.

## Goals / Non-Goals

**Goals:**
- Add shipping fees pop-up gated by feature flag on client sourcing request creation
- Support video uploads and pre-submission file deletion in quotation forms
- Fix quotation list ordering to show newest first
- Unify edit quotation form with create quotation form
- Fix 502 Bad Gateway on quotation update

**Non-Goals:**
- No changes to the PDF generation or client-facing quotation pages
- No changes to the sourcing request workflow beyond the pop-up
- No changes to the database schema (feature flag key added via seeder only)

## Decisions

1. **Feature flag approach**: Use existing `FeatureFlag` system with key `shipping_fees_popup`. No new middleware needed — use `@featureVisible` Blade directive and `feature_flag_visible()` helper. The pop-up data will be fetched via an existing or new AJAX endpoint returning shipping fee rates.
2. **Video uploads**: `ImageProcessingService::compressAndStore()` already passes non-image files through unchanged. The `media_files` validation already accepts `mp4,mov,avi`. The create form's file input just needs `accept` attribute updated, and the Alpine.js preview component needs a video player fallback.
3. **File deletion before submit**: Add Alpine.js state to track selected files, show delete (X) button on each preview, and update the file input's files list via DataTransfer.
4. **Edit form unification**: Rewrite `edit.blade.php` to use the same structure as `create.blade.php`, preserving the `real_product_image` handling and negotiation reply section that are edit-specific. Extract shared partials where sensible.
5. **502 Bad Gateway fix**: Likely caused by PHP memory limit, execution timeout, or a fatal error in the `update()` method (possibly file upload processing or a missing dependency). Debug by checking Laravel logs, increasing PHP limits, and inspecting the update flow.
6. **List ordering**: The controller already has `orderBy('created_at', 'desc')` within the custom sort. Verify the custom sort doesn't override it and simplify if needed.

## Risks / Trade-offs

- [Video upload size] → Add server-side validation matching the existing 10MB limit; warn users about larger files
- [502 error root cause unknown] → Add detailed logging in the update method before fixing; check `phpinfo` for memory limits
- [Edit form unification may miss edit-specific fields] → Preserve negotiation reply, featured image delete, and existing media management sections
