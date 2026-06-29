## 1. Database Migration

- [x] 1.1 Create migration `add_cloudinary_public_id_to_media_tables` adding nullable `string` column `cloudinary_public_id` to tables: `sourcing_requests`, `quotations`, `quotation_media`, `sourcing_orders`, `sourcing_order_media`, `payment_methods`, `users`, `refund_requests`
- [x] 1.2 Add `cloudinary_public_id` to `$fillable` on each of the 8 models

## 2. Create DeleteCloudinaryAsset Job

- [x] 2.1 Create `app/Jobs/DeleteCloudinaryAsset.php` — implements `ShouldQueue`, accepts `$publicId` in constructor, uses `cloudinary()->uploadApi()->destroy()` to delete, skips when `CLOUDINARY_URL` config is empty or `$publicId` is null/empty, logs debug on skip

## 3. Rewire ImageProcessingService for Hybrid Upload

- [x] 3.1 In `compressAndStore()`, after compressing image, check if Cloudinary is configured and file is an image — if so attempt Cloudinary upload using `Storage::disk('cloudinary')` and capture the returned public ID
- [x] 3.2 Wrap Cloudinary upload in try/catch: on any exception, log warning with exception message and fall back to local disk
- [x] 3.3 Update `ImageResult` construction to pass `publicId` when Cloudinary succeeds (keep local path always)
- [x] 3.4 Non-image files always go to local disk only (no Cloudinary attempt)

## 4. Tests

- [x] 4.1 Write test: upload with valid Cloudinary returns non-null publicId
- [x] 4.2 Write test: upload with invalid/missing CLOUDINARY_URL falls back to local
- [x] 4.3 Write test: non-image file uploads to local only
- [x] 4.4 Write test: DeleteCloudinaryAsset skips when config missing
- [x] 4.5 Write test: DeleteCloudinaryAsset skips on empty public ID
- [x] 4.6 Write test: cloudinary_public_id column exists and is nullable in migrated tables
