<?php

namespace App\Console\Commands;

use App\Models\RefundRequest;
use App\Models\SourcingRequest;
use App\Models\Quotation;
use App\Models\QuotationMedia;
use App\Models\SourcingOrder;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MigrateImagesToCloudinary extends Command
{
    protected $signature = 'images:migrate-to-cloudinary
                            {--dry-run : Simulate migration without uploading}
                            {--batch-size=50 : Number of records per batch}
                            {--delay=0 : Seconds to wait between batches}
                            {--rollback : Restore local paths from last migration log}';

    protected $description = 'Migrate all local image files from local storage to Cloudinary';

    private array $migrationLog = [];
    private string $logFilePath;

    public function handle()
    {
        if ($this->option('rollback')) {
            return $this->rollbackMigration();
        }

        $this->info('Starting Cloudinary Image Migration...');

        if (!config('cloudinary.cloud_url') && !env('CLOUDINARY_URL')) {
            $this->error('Cloudinary URL is not configured. Please set CLOUDINARY_URL in your .env file.');
            return 1;
        }

        $this->logFilePath = storage_path('logs/cloudinary-migration-' . now()->format('Ymd_His') . '.json');

        $batchSize = (int) $this->option('batch-size');
        $delay = (int) $this->option('delay');

        $this->migrateSourcingRequests($batchSize, $delay);
        $this->migrateQuotations($batchSize, $delay);
        $this->migrateQuotationMedia($batchSize, $delay);
        $this->migrateSourcingOrders($batchSize, $delay);
        $this->migrateSourcingOrderRefundProofs($batchSize, $delay);
        $this->migratePaymentMethods($batchSize, $delay);
        $this->migrateUserProfiles($batchSize, $delay);
        $this->migrateRefundRequests($batchSize, $delay);

        if ($this->option('dry-run')) {
            $this->info("\nDry-run complete. No files were uploaded.");
            return 0;
        }

        file_put_contents($this->logFilePath, json_encode($this->migrationLog, JSON_PRETTY_PRINT));
        $this->info("\nMigration log saved to: {$this->logFilePath}");
        $this->info('Cloudinary Image Migration completed successfully!');
        return 0;
    }

    private function logMigration(string $model, int $id, string $field, ?string $originalPath, ?string $cloudinaryUrl, ?string $publicId): void
    {
        $this->migrationLog[] = compact('model', 'id', 'field', 'originalPath', 'cloudinaryUrl', 'publicId');
    }

    private function uploadToCloudinary(string $localPath, string $folder): ?array
    {
        if (str_starts_with($localPath, 'http://') || str_starts_with($localPath, 'https://')) {
            return ['url' => $localPath, 'publicId' => null];
        }

        $isDryRun = $this->option('dry-run');

        if (!$isDryRun && !Storage::disk('public')->exists($localPath)) {
            $this->warn("Local file does not exist: {$localPath}");
            return null;
        }

        if ($isDryRun) {
            $this->line("  [DRY-RUN] Would upload: {$localPath} -> [{$folder}]");
            return ['url' => $localPath, 'publicId' => null];
        }

        $fullPath = Storage::disk('public')->path($localPath);

        try {
            $this->info("Uploading to Cloudinary [{$folder}]: {$localPath}");
            $response = cloudinary()->uploadApi()->upload($fullPath, [
                'folder' => $folder,
            ]);

            $this->info("Success: {$response['secure_url']}");
            return [
                'url' => $response['secure_url'],
                'publicId' => $response['public_id'],
            ];
        } catch (\Exception $e) {
            $this->error("Failed to upload {$localPath} to Cloudinary: " . $e->getMessage());
            Log::error("Cloudinary migration upload failed for {$localPath}: " . $e->getMessage());
            return null;
        }
    }

    private function processBatch(\Illuminate\Support\Collection $items, string $modelName, string $field, string $folder, callable $save, int $delay): int
    {
        $count = 0;
        foreach ($items as $item) {
            $path = $item->{$field};
            if (empty($path) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                continue;
            }

            $result = $this->uploadToCloudinary($path, $folder);
            if ($result) {
                $save($item, $result);
                $this->logMigration($modelName, $item->id, $field, $path, $result['url'], $result['publicId']);
                $count++;
            }
        }

        if ($delay > 0 && $items->count() > 0) {
            sleep($delay);
        }

        return $count;
    }

    private function migrateSourcingRequests(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Sourcing Requests...');
        $total = 0;
        SourcingRequest::whereNotNull('product_image')
            ->where('product_image', '!=', '')
            ->chunk($batchSize, function ($requests) use (&$total, $delay) {
                $total += $this->processBatch($requests, 'SourcingRequest', 'product_image', 'sourcing-requests',
                    fn ($r, $result) => $r->update([
                        'product_image' => $result['url'],
                        'cloudinary_public_id' => $result['publicId'],
                    ]),
                    $delay
                );
            });
        $this->info("Migrated {$total} sourcing request images.");
    }

    private function migrateQuotations(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Quotations...');
        $countReal = 0;
        $countQuality = 0;

        Quotation::chunk($batchSize, function ($quotations) use (&$countReal, &$countQuality, $delay) {
            foreach ($quotations as $quotation) {
                $changed = false;
                $saveData = [];

                if (!empty($quotation->real_product_image) && !str_starts_with($quotation->real_product_image, 'http')) {
                    $result = $this->uploadToCloudinary($quotation->real_product_image, 'quotations');
                    if ($result) {
                        $saveData['real_product_image'] = $result['url'];
                        $this->logMigration('Quotation', $quotation->id, 'real_product_image', $quotation->real_product_image, $result['url'], $result['publicId']);
                        $countReal++;
                        $changed = true;
                    }
                }

                $options = $quotation->quality_options;
                if (is_array($options)) {
                    $updated = false;
                    foreach ($options as $key => $option) {
                        if (isset($option['image_path']) && !empty($option['image_path']) && !str_starts_with($option['image_path'], 'http')) {
                            $result = $this->uploadToCloudinary($option['image_path'], 'quotation-quality-options');
                            if ($result) {
                                $options[$key]['image_path'] = $result['url'];
                                $changed = true;
                                $countQuality++;
                            }
                        }
                    }
                    if ($changed) {
                        $saveData['quality_options'] = $options;
                    }
                }

                if ($changed) {
                    $quotation->update($saveData);
                }
            }
        });

        $this->info("Migrated {$countReal} quotation images and {$countQuality} quality option images.");
    }

    private function migrateQuotationMedia(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Quotation Media...');
        $total = 0;
        QuotationMedia::whereNotNull('file_path')
            ->where('file_path', '!=', '')
            ->chunk($batchSize, function ($medias) use (&$total, $delay) {
                foreach ($medias as $media) {
                    $path = $media->file_path;
                    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                        continue;
                    }
                    $folder = $media->file_type === 'video' ? 'quotation-videos' : 'quotation-images';
                    $result = $this->uploadToCloudinary($path, $folder);
                    if ($result) {
                        $media->update([
                            'file_path' => $result['url'],
                            'cloudinary_public_id' => $result['publicId'],
                        ]);
                        $this->logMigration('QuotationMedia', $media->id, 'file_path', $path, $result['url'], $result['publicId']);
                        $total++;
                    }
                }
                if ($delay > 0) { sleep($delay); }
            });
        $this->info("Migrated {$total} quotation media files.");
    }

    private function migrateSourcingOrders(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Sourcing Orders (proof_of_payment)...');
        $total = 0;
        SourcingOrder::whereNotNull('proof_of_payment_path')
            ->where('proof_of_payment_path', '!=', '')
            ->chunk($batchSize, function ($orders) use (&$total, $delay) {
                $total += $this->processBatch($orders, 'SourcingOrder', 'proof_of_payment_path', 'proof-of-payments',
                    fn ($o, $result) => $o->update([
                        'proof_of_payment_path' => $result['url'],
                        'cloudinary_public_id' => $result['publicId'],
                    ]),
                    $delay
                );
            });
        $this->info("Migrated {$total} proof of payment images.");
    }

    private function migrateSourcingOrderRefundProofs(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Sourcing Orders (refund_proof_path)...');
        $total = 0;
        SourcingOrder::whereNotNull('refund_proof_path')
            ->where('refund_proof_path', '!=', '')
            ->chunk($batchSize, function ($orders) use (&$total, $delay) {
                $total += $this->processBatch($orders, 'SourcingOrder', 'refund_proof_path', 'refund-proofs',
                    fn ($o, $result) => $o->update([
                        'refund_proof_path' => $result['url'],
                    ]),
                    $delay
                );
            });
        $this->info("Migrated {$total} refund proof images.");
    }

    private function migratePaymentMethods(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Payment Methods...');
        $total = 0;
        PaymentMethod::whereNotNull('logo_path')
            ->where('logo_path', '!=', '')
            ->chunk($batchSize, function ($methods) use (&$total, $delay) {
                $total += $this->processBatch($methods, 'PaymentMethod', 'logo_path', 'payment-logos',
                    fn ($m, $result) => $m->update([
                        'logo_path' => $result['url'],
                        'cloudinary_public_id' => $result['publicId'],
                    ]),
                    $delay
                );
            });
        $this->info("Migrated {$total} payment method logos.");
    }

    private function migrateUserProfiles(int $batchSize, int $delay): void
    {
        $this->comment('Migrating User Profile Photos...');
        $total = 0;
        User::whereNotNull('profile_photo_path')
            ->where('profile_photo_path', '!=', '')
            ->chunk($batchSize, function ($users) use (&$total, $delay) {
                $total += $this->processBatch($users, 'User', 'profile_photo_path', 'profile-photos',
                    fn ($u, $result) => $u->update([
                        'profile_photo_path' => $result['url'],
                        'cloudinary_public_id' => $result['publicId'],
                    ]),
                    $delay
                );
            });
        $this->info("Migrated {$total} user profile photos.");
    }

    private function migrateRefundRequests(int $batchSize, int $delay): void
    {
        $this->comment('Migrating Refund Requests...');
        $countProof = 0;
        $countEvidence = 0;

        RefundRequest::chunk($batchSize, function ($requests) use (&$countProof, &$countEvidence, $delay) {
            foreach ($requests as $refund) {
                $changed = false;
                $saveData = [];

                if (!empty($refund->refund_proof_path) && !str_starts_with($refund->refund_proof_path, 'http')) {
                    $result = $this->uploadToCloudinary($refund->refund_proof_path, 'refund-proofs');
                    if ($result) {
                        $saveData['refund_proof_path'] = $result['url'];
                        $this->logMigration('RefundRequest', $refund->id, 'refund_proof_path', $refund->refund_proof_path, $result['url'], $result['publicId']);
                        $countProof++;
                        $changed = true;
                    }
                }

                $evidencePaths = $refund->evidence_paths ?? [];
                if (is_array($evidencePaths) && count($evidencePaths) > 0) {
                    $updatedEvidence = [];
                    foreach ($evidencePaths as $evidencePath) {
                        if (str_starts_with($evidencePath, 'http://') || str_starts_with($evidencePath, 'https://')) {
                            $updatedEvidence[] = $evidencePath;
                            continue;
                        }
                        $result = $this->uploadToCloudinary($evidencePath, 'refund-evidence');
                        if ($result) {
                            $updatedEvidence[] = $result['url'];
                            $this->logMigration('RefundRequest', $refund->id, 'evidence_paths', $evidencePath, $result['url'], $result['publicId']);
                            $countEvidence++;
                        } else {
                            $updatedEvidence[] = $evidencePath;
                        }
                    }
                    $saveData['evidence_paths'] = $updatedEvidence;
                    $changed = true;
                }

                if ($changed) {
                    $refund->update($saveData);
                }
            }
            if ($delay > 0) { sleep($delay); }
        });

        $this->info("Migrated {$countProof} refund proof images and {$countEvidence} evidence files.");
    }

    private function rollbackMigration(): int
    {
        $logFiles = glob(storage_path('logs/cloudinary-migration-*.json'));
        if (empty($logFiles)) {
            $this->error('No migration log found.');
            return 1;
        }

        rsort($logFiles);
        $logFile = $logFiles[0];
        $this->info("Rolling back using: {$logFile}");

        $entries = json_decode(file_get_contents($logFile), true);
        if (empty($entries)) {
            $this->error('Migration log is empty.');
            return 1;
        }

        $rollbackCount = 0;
        foreach ($entries as $entry) {
            $modelClass = "App\\Models\\{$entry['model']}";
            if (!class_exists($modelClass)) {
                $this->warn("Model class not found: {$modelClass}");
                continue;
            }

            $record = $modelClass::find($entry['id']);
            if (!$record) {
                $this->warn("Record not found: {$entry['model']}#{$entry['id']}");
                continue;
            }

            $record->update([
                $entry['field'] => $entry['originalPath'],
                'cloudinary_public_id' => null,
            ]);

            if ($entry['publicId']) {
                try {
                    cloudinary()->uploadApi()->destroy($entry['publicId']);
                    $this->line("  Deleted Cloudinary asset: {$entry['publicId']}");
                } catch (\Exception $e) {
                    $this->warn("  Failed to delete Cloudinary asset {$entry['publicId']}: " . $e->getMessage());
                }
            }

            $rollbackCount++;
        }

        $this->info("Rollback complete. {$rollbackCount} records restored.");
        return 0;
    }
}
