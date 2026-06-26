## 1. Migration & Database Schema

- [x] 1.1 Add `cloudinary_public_id` nullable string column to `sourcing_requests`, `quotations`, `quotation_media`, `sourcing_orders`, `sourcing_order_media`, `payment_methods`, `users`, `refund_requests`
- [x] 1.2 Create `ImageResult` DTO class with `path` and `publicId` properties
- [x] 1.3 Update `ImageProcessingService::compressAndStore()` to return `ImageResult` storing both `secure_url` and Public ID
- [x] 1.4 Update all controllers calling `compressAndStore()` to handle `ImageResult`
- [x] 1.5 Fix `PaymentMethodController` to use `ImageProcessingService::compressAndStore()` instead of `Storage::disk('public')->store()`
- [x] 1.6 Add missing migrations to the existing command: `SourcingOrder.refund_proof_path`, `RefundRequest.refund_proof_path`, `RefundRequest.evidence_paths`
- [x] 1.7 Add `--dry-run`, `--batch-size`, `--delay` options to migration command
- [x] 1.8 Add migration logging to JSON file in `storage/logs/`
- [x] 1.9 Add `--rollback` mode to restore local paths from migration log

## 2. Asset Cleanup on Deletion

- [x] 2.1 Create `DeleteCloudinaryAsset` job with retry logic (3 attempts, exponential backoff)
- [x] 2.2 Add `deleting` event to `SourcingRequest` model for Cloudinary cleanup
- [x] 2.3 Add `deleting` event to `Quotation` model for Cloudinary cleanup
- [x] 2.4 Add `deleting` event to `QuotationMedia` model for Cloudinary cleanup
- [x] 2.5 Add `deleting` event to `SourcingOrder` model for Cloudinary cleanup (refund_proof_path)
- [x] 2.6 Add `deleting` event to `PaymentMethod` model for Cloudinary cleanup
- [x] 2.7 Add `deleting` event to `User` model for Cloudinary cleanup
- [x] 2.8 Add `deleting` event to `RefundRequest` model for Cloudinary cleanup (array field)

## 3. Transformations & media_url()

- [x] 3.1 Add `$transformations` parameter to `media_url()` helper
- [x] 3.2 Implement Cloudinary URL transformation builder (parse URL, inject transformation segments)
- [x] 3.3 Apply `f_auto,q_auto` defaults for all Cloudinary URLs
- [ ] 3.4 Update all Blade view calls to `media_url()` — no change needed (optional param), but verify

## 4. Configuration & Production Readiness

- [x] 4.1 Set `CLOUDINARY_URL` in `.env.production` and `env.production`
- [x] 4.2 Delete old logo on `PaymentMethod` update from Cloudinary too
- [x] 4.3 Add `DeleteCloudinaryAsset` job to `SourcingRequest` deleting event (existing one only cleans local)
- [x] 4.4 Add `DeleteCloudinaryAsset` job to `SourcingOrder` deleting event (existing one only cleans local)
- [x] 4.5 Add `DeleteCloudinaryAsset` job to `SourcingOrderMedia` deleting event (existing one only cleans local)

## 5. Verification

- [ ] 5.1 Write/update tests for `ImageProcessingService` (Cloudinary available vs unavailable)
- [ ] 5.2 Write tests for `DeleteCloudinaryAsset` job (retry, skip if not configured)
- [ ] 5.3 Write tests for `media_url()` with transformations
- [ ] 5.4 Write tests for migration command (dry-run, rollback)
- [ ] 5.5 Run full test suite to confirm no regressions
- [ ] 5.6 Run migration with `--dry-run` on production-like dataset to estimate execution time
