# Cloudinary Migration Plan

This document outlines the step-by-step plan to migrate all existing images from the local disk storage to Cloudinary, and update the application to use Cloudinary for all future uploads.

## Phase 1: Setup & Configuration

1. **Create a Cloudinary Account**
   - Register at [Cloudinary](https://cloudinary.com/).
   - Obtain your `Cloud Name`, `API Key`, and `API Secret` from the dashboard.

2. **Install Cloudinary Laravel Package**
   - Run the following command to install the official package:
     ```bash
     composer require cloudinary-labs/cloudinary-laravel
     ```

3. **Configure Environment Variables**
   - Add your Cloudinary URL to the `.env` file:
     ```env
     CLOUDINARY_URL=cloudinary://528486912228994:pGAU-Fq-_hvPXsrDxyIbccLR-Is@dnkp4jrup
     ```

4. **Update Filesystems Configuration**
   - In `config/filesystems.php`, ensure the Cloudinary disk is configured (the package usually handles this, but you can explicitly set it as the default cloud disk if needed).
   - Change your default file upload disk in `.env` if you want to use it system-wide:
     ```env
     FILESYSTEM_DISK=cloudinary
     ```

## Phase 2: Application Logic Updates

1. **Update `ImageProcessingService`**
   - Modify the `compressAndStore` method (and any other upload methods) in `app/Services/ImageProcessingService.php` to upload directly to Cloudinary instead of the local disk.
   - Example snippet:
     ```php
     $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
     ```
   - *Note: Cloudinary automatically handles compression and optimization, so you may be able to simplify your existing image processing logic.*

2. **Update Blade Vi
ews**
   - Search for all instances of `asset('storage/' . $path)` or `Storage::url($path)` in your `.blade.php` files.
   - Update them to directly output the Cloudinary URL (if storing the full URL) or use the Cloudinary facade if storing the Public ID.

## Phase 3: Data Migration Strategy

1. **Identify Models with Images**
   - `SourcingRequest`: `product_image`
   - `Quotation`: `real_product_image`, `quality_options` (JSON field containing `image_path`)
   - `SourcingOrder`: `proof_of_payment_path`
   - `PaymentMethod`: `logo_path`
   - *(Check if any other models or settings have images).*

2. **Create an Artisan Migration Command**
   - Generate a command: `php artisan make:command MigrateImagesToCloudinary`
   - The command will iterate through all records in the identified models.
   - For each record:
     1. Check if the image path exists on the local disk.
     2. If it does, upload the file to Cloudinary using `cloudinary()->upload(Storage::path($model->image_path))`.
     3. Update the model's database field with the new Cloudinary Secure URL.
     4. Save the model.
   - Make sure to handle the JSON field (`quality_options`) in the `Quotation` model carefully, updating the specific nested `image_path`.

## Phase 4: Execution & Testing

1. **Run the Migration Command**
   - Execute the migration command locally or on a staging server first.
     ```bash
     php artisan images:migrate-to-cloudinary
     ```
   - Monitor the console output for any failed uploads.

2. **QA & Verification**
   - Verify that all old images are displaying correctly in the application (Sourcing Requests, Quotations, Orders, Admin Panel).
   - Test a **new** upload (e.g., submitting a new sourcing request, uploading a proof of payment) to ensure the new `ImageProcessingService` flow works perfectly and saves the image directly to Cloudinary.

## Phase 5: Cleanup

1. **Backup Local Storage**
   - Before deleting anything, take a backup of your `storage/app/public` (or `storage/app/images`) directories.

2. **Delete Local Images**
   - Once you are 100% sure that all images are loading from Cloudinary and the application is functioning correctly, you can safely delete the migrated image files from your local disk to free up server space.
