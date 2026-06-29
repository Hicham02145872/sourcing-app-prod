<?php

namespace App\Console\Commands;

use App\Models\PaymentMethod;
use App\Models\Quotation;
use App\Models\QuotationMedia;
use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use App\Models\SourcingOrderMedia;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CloudinaryBackfillCommand extends Command
{
    protected $signature = 'cloudinary:backfill
        {--dry-run : Only show what would be uploaded, do not upload}
        {--model= : Only process a specific model (sourcing-request, quotation, quotation-media, sourcing-order, sourcing-order-media, payment-method, user, refund-request)}';

    protected $description = 'Upload existing local images to Cloudinary and save the public ID';

    public function handle(): int
    {
        if (empty(config('filesystems.disks.cloudinary.url'))) {
            $this->error('CLOUDINARY_URL is not configured. Aborting.');
            return Command::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        $only = $this->option('model');

        if ($dryRun) {
            $this->warn('DRY RUN: No files will be uploaded.');
        }

        $processed = 0;

        if (! $only || $only === 'sourcing-request') {
            $processed += $this->backfillSourcingRequests($dryRun);
        }
        if (! $only || $only === 'quotation') {
            $processed += $this->backfillQuotations($dryRun);
        }
        if (! $only || $only === 'quotation-media') {
            $processed += $this->backfillQuotationMedia($dryRun);
        }
        if (! $only || $only === 'sourcing-order') {
            $processed += $this->backfillSourcingOrders($dryRun);
        }
        if (! $only || $only === 'sourcing-order-media') {
            $processed += $this->backfillSourcingOrderMedia($dryRun);
        }
        if (! $only || $only === 'payment-method') {
            $processed += $this->backfillPaymentMethods($dryRun);
        }
        if (! $only || $only === 'user') {
            $processed += $this->backfillUsers($dryRun);
        }
        if (! $only || $only === 'refund-request') {
            $processed += $this->backfillRefundRequests($dryRun);
        }

        $this->info("Done. {$processed} file(s) processed.");

        return Command::SUCCESS;
    }

    private function uploadToCloudinary(string $path): ?string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $this->warn("  File not found: {$path}");
            return null;
        }

        try {
            $info = pathinfo($path);
            $dirname = str_replace('\\', '/', $info['dirname'] ?? '');
            $dirname = $dirname === '.' ? '' : $dirname;
            $publicId = $dirname ? $dirname.'/'.$info['filename'] : $info['filename'];

            cloudinary()->uploadApi()->upload(
                $disk->path($path),
                ['public_id' => $publicId, 'overwrite' => true]
            );

            return $publicId;
        } catch (\Exception $e) {
            $this->error("  Upload failed for {$path}: {$e->getMessage()}");
            Log::warning('Cloudinary backfill upload failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function backfillSourcingRequests(bool $dryRun): int
    {
        $count = 0;
        $rows = SourcingRequest::whereNotNull('product_image')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("SourcingRequest #{$row->id}: {$row->product_image}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->product_image);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillQuotations(bool $dryRun): int
    {
        $count = 0;

        $rows = Quotation::whereNotNull('real_product_image')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("Quotation #{$row->id}: {$row->real_product_image}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->real_product_image);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillQuotationMedia(bool $dryRun): int
    {
        $count = 0;
        $rows = QuotationMedia::whereNotNull('file_path')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("QuotationMedia #{$row->id}: {$row->file_path}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->file_path);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillSourcingOrders(bool $dryRun): int
    {
        $count = 0;
        $rows = SourcingOrder::whereNotNull('proof_of_payment_path')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("SourcingOrder #{$row->id}: {$row->proof_of_payment_path}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->proof_of_payment_path);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillSourcingOrderMedia(bool $dryRun): int
    {
        $count = 0;
        $rows = SourcingOrderMedia::whereNotNull('file_path')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("SourcingOrderMedia #{$row->id}: {$row->file_path}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->file_path);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillPaymentMethods(bool $dryRun): int
    {
        $count = 0;
        $rows = PaymentMethod::whereNotNull('logo_path')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("PaymentMethod #{$row->id}: {$row->logo_path}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->logo_path);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillUsers(bool $dryRun): int
    {
        $count = 0;
        $rows = User::whereNotNull('profile_photo_path')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("User #{$row->id}: {$row->profile_photo_path}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->profile_photo_path);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }

    private function backfillRefundRequests(bool $dryRun): int
    {
        $count = 0;
        $rows = RefundRequest::whereNotNull('refund_proof_path')
            ->whereNull('cloudinary_public_id')
            ->get();

        foreach ($rows as $row) {
            $this->line("RefundRequest #{$row->id}: {$row->refund_proof_path}");
            if (! $dryRun) {
                $publicId = $this->uploadToCloudinary($row->refund_proof_path);
                if ($publicId) {
                    $row->cloudinary_public_id = $publicId;
                    $row->saveQuietly();
                    $this->info("  -> cloudinary_public_id: {$publicId}");
                }
            }
            $count++;
        }

        return $count;
    }
}
