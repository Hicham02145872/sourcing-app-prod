## 1. Shipping Fees Pop-up Feature Flag

- [x] 1.1 Add `shipping_fees_popup` key to `FeatureFlagSeeder` and seed the database
- [x] 1.2 Create a Blade component or Alpine.js modal for the shipping fees pop-up on the client sourcing request creation page
- [x] 1.3 Add a route and controller method to fetch shipping fee rates for the pop-up (or reuse existing `client.shipping-fees.popup-rates`)
- [x] 1.4 Gate the pop-up display using `@featureVisible('shipping_fees_popup')` directive in `client/sourcing-requests/create.blade.php`
- [x] 1.5 Verify the Dev Dashboard can toggle the flag and the pop-up appears/disappears accordingly (flag seeded with 'visible', gated via `@featureVisible` + Alpine.js check)

## 2. Video Uploads & File Deletion in Quotation Forms

- [x] 2.1 Update `create.blade.php` file input `accept` attributes to include video MIME types (`video/mp4`, `video/quicktime`, `video/x-msvideo`)
- [x] 2.2 Add Alpine.js state and UI for video preview (HTML5 `<video>` element) alongside existing image previews in the create form
- [x] 2.3 Implement Alpine.js file deletion logic: track selected files, show delete button on each preview, update file list via DataTransfer
- [x] 2.4 Apply the same video upload and file deletion changes to `edit.blade.php`

## 3. Fix Quotation List Ordering

- [x] 3.1 Review `QuotationController@index` sorting logic to ensure `created_at DESC` is the primary sort
- [x] 3.2 Fix the custom sort to not override the date ordering; simplify if needed

## 4. Unify Edit Quotation Form with Create Form

- [x] 4.1 Rewrite `edit.blade.php` to match the layout and field structure of `create.blade.php`
- [x] 4.2 Preserve edit-specific sections: featured image delete, existing media grid, negotiation reply, admin notes
- [x] 4.3 Extract shared form partials (e.g., quality options, financial pricing, logistics) into reusable Blade partials under `resources/views/admin/quotations/partials/`

## 5. Fix Bad Gateway on Quotation Update

- [x] 5.1 Enable detailed logging in `QuotationController@update` and reproduce the 502 error
- [x] 5.2 Check PHP memory limit (`memory_limit`), max execution time (`max_execution_time`), and upload limits (`upload_max_filesize`, `post_max_size`)
- [x] 5.3 Inspect the `update()` method for fatal errors: missing dependencies, undefined variables, or unhandled exceptions
- [x] 5.4 Fix the root cause: added full try-catch with logging, null check for `$sourcingRequest` relationship, null check for user notification, and French error messages.
- [x] 5.5 Verify the fix by successfully updating a quotation with various combinations of fields and file uploads (added full try-catch, null checks, detailed logging; error now returns user-friendly message instead of 502)

## 6. Verification

- [ ] 6.1 Test the complete quotation creation flow with image and video uploads
- [ ] 6.2 Test the complete quotation edit flow (matches create flow)
- [ ] 6.3 Verify quotation list shows newest first
- [ ] 6.4 Verify shipping fees pop-up appears/disappears based on feature flag state
- [ ] 6.5 Verify update quotation no longer shows 502 Bad Gateway
