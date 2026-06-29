## Context

The project has Cloudinary configured in `.env` with real credentials and a `cloudinary` disk in `filesystems.php`. However, `ImageProcessingService::compressAndStore()` only writes to the local `public/` disk — it never attempts Cloudinary. Meanwhile, 8 model classes dispatch `App\Jobs\DeleteCloudinaryAsset` (a job that does not exist), meaning any code path that sets a `cloudinary_public_id` would crash on cleanup.

The `ImageResult` DTO already supports both storage backends (`path` for URL/local-path, `publicId` for Cloudinary), but `publicId` is never populated. The `media_url()` helper correctly renders both local paths and Cloudinary URLs.

## Goals / Non-Goals

**Goals:**
- `compressAndStore()` uploads to Cloudinary when configured+reachable, falls back to local disk otherwise
- `DeleteCloudinaryAsset` job exists and gracefully handles missing/empty Cloudinary config
- Database migration adds `cloudinary_public_id` nullable columns to the 8 relevant tables
- All existing callers of `compressAndStore()` continue to work unchanged
- Cloudinary failures are logged but never break the user flow

**Non-Goals:**
- Backfill historical uploads to Cloudinary (separate migration command)
- Remove local storage as an option
- Add Cloudinary transformation UI
- Add any new admin UI or configuration pages

## Decisions

### Decision 1: Cloudinary health check at upload time (not boot time)
- **Option A**: Ping Cloudinary on every `compressAndStore()` call (lightweight API check)
- **Option B**: Check only `CLOUDINARY_URL` env var presence, attempt upload, catch on failure
- **Choice**: Option B — simpler, avoids an extra HTTP round-trip. The upload itself serves as the health check. A missing/empty `CLOUDINARY_URL` or network error triggers catch → local fallback.
- **Rationale**: Cloudinary's SDK returns actionable exceptions (authentication, connection, rate-limit). Checking URL presence first avoids unnecessary SDK initialization.

### Decision 2: Store both local path and cloudinary_public_id
- **Option A**: Store only Cloudinary public ID, omit local path when Cloudinary succeeds
- **Option B**: Always store both local path AND cloudinary_public_id (when Cloudinary succeeds)
- **Choice**: Option B — the local copy serves as a fallback URL via `media_url()` if Cloudinary is temporarily down. The extra storage cost is negligible (JPEGs already on disk).

### Decision 3: New config key for Cloudinary upload enable
- **Option A**: Auto-detect — if `CLOUDINARY_URL` is non-empty, attempt upload
- **Option B**: Explicit `CLOUDINARY_ENABLED=true` env var
- **Choice**: Option A — one less env var to maintain. Setting `CLOUDINARY_URL` is the opt-in signal. An empty value means local-only, same as now.

### Decision 4: Single migration vs per-model migration
- **Choice**: Single migration `add_cloudinary_public_id_to_media_tables` that adds the column to all 8 tables. Simpler to manage and roll back.

## Risks / Trade-offs

- **[Credit cost]** Cloudinary uploads consume credits. Mitigation: local fallback is always available; Cloudinary is the primary but not required path.
- **[Existence of DeleteCloudinaryAsset]** The job was referenced in 8 models but never created. Mitigation: creating the job now; already dispatched calls will fail until code is deployed (no live prod yet).
- **[Latency]** Cloudinary upload adds network time. Mitigation: local fallback is near-instant; acceptable trade-off for CDN delivery.
- **[Backward compatibility]** Existing media records have no `cloudinary_public_id`. Mitigation: column is nullable; `ImageResult::isCloudinary()` returns `false` for null publicId; `media_url()` handles both paths.
