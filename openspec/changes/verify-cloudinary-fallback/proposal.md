## Why

Cloudinary integration is partially planned but not functional. `ImageProcessingService::compressAndStore()` only writes to local disk, never attempts Cloudinary — yet many models dispatch `DeleteCloudinaryAsset` jobs that don't exist. This creates a gap: uploads never reach Cloudinary, and any `cloudinary_public_id` cleanup would crash.

## What Changes

1. **Cloudinary health check** — a runtime check that verifies `CLOUDINARY_URL` is configured and Cloudinary is reachable before attempting uploads
2. **Hybrid upload with fallback** — `ImageProcessingService` attempts Cloudinary first, falls back to local disk on connection/timeout/auth errors
3. **Create `DeleteCloudinaryAsset` job** — the missing job class with retry logic and graceful skip when Cloudinary is not configured
4. **Add `cloudinary_public_id` to relevant tables** — database migration to store the Cloudinary public ID alongside local paths
5. **Return proper `publicId`** from `compressAndStore` when Cloudinary succeeds, so callers know which storage backend was used
6. **Error monitoring** — log Cloudinary failures and which fallback was used for observability

## Capabilities

### New Capabilities
- `cloudinary-upload-fallback`: Hybrid upload with health check, automatic Cloudinary upload, and local disk fallback on failure

### Modified Capabilities
- (none — no existing specs cover Cloudinary behavior)

## Impact

- `app/Services/ImageProcessingService.php` — rewired to attempt Cloudinary first, fall back to local
- `app/Jobs/DeleteCloudinaryAsset.php` — created
- `database/migrations/` — new migration for `cloudinary_public_id` on 8 tables (sourcing_requests, quotations, quotation_media, sourcing_orders, sourcing_order_media, payment_methods, users, refund_requests)
- All callers of `compressAndStore()` benefit transparently (same return type, richer `ImageResult`)
- Storage: Cloudinary disk (credits-based) used when available, `public/` disk as fallback
