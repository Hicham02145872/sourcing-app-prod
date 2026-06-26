<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DeleteCloudinaryAsset implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ?string $publicId,
    ) {}

    public function handle(): void
    {
        if (!$this->publicId) {
            return;
        }

        if (!config('cloudinary.cloud_url') && !env('CLOUDINARY_URL')) {
            return;
        }

        try {
            cloudinary()->uploadApi()->destroy($this->publicId);
            Log::info("Deleted Cloudinary asset: {$this->publicId}");
        } catch (\Exception $e) {
            Log::error("Failed to delete Cloudinary asset {$this->publicId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function backoff(): array
    {
        return [30, 120, 300];
    }
}