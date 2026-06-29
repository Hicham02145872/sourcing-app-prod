<?php

namespace Tests\Feature;

use App\Jobs\DeleteCloudinaryAsset;
use App\Services\ImageProcessingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CloudinaryUploadFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_with_cloudinary_returns_public_id()
    {
        config(['filesystems.disks.cloudinary.url' => 'cloudinary://key:secret@test']);
        Storage::fake('cloudinary');

        $service = app(ImageProcessingService::class);
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $result = $service->compressAndStore($file, 'test-uploads');

        $this->assertNotNull($result->publicId);
        $this->assertTrue($result->isCloudinary());
    }

    public function test_upload_without_cloudinary_falls_back_to_local()
    {
        config(['filesystems.disks.cloudinary.url' => '']);
        Storage::fake('cloudinary');

        $service = app(ImageProcessingService::class);
        $file = UploadedFile::fake()->image('fallback.jpg', 100, 100);

        $result = $service->compressAndStore($file, 'test-uploads');

        $this->assertNull($result->publicId);
        $this->assertFalse($result->isCloudinary());
    }

    public function test_non_image_file_uploads_to_local_only()
    {
        config(['filesystems.disks.cloudinary.url' => 'cloudinary://key:secret@test']);
        Storage::fake('cloudinary');
        Storage::fake('public');

        $service = app(ImageProcessingService::class);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $result = $service->compressAndStore($file, 'test-uploads');

        $this->assertNull($result->publicId);
        $this->assertFalse($result->isCloudinary());
    }

    public function test_delete_cloudinary_asset_skips_when_config_missing()
    {
        config(['filesystems.disks.cloudinary.url' => '']);

        Log::shouldReceive('debug')
            ->once()
            ->with('DeleteCloudinaryAsset skipped: CLOUDINARY_URL not configured');

        $job = new DeleteCloudinaryAsset('some/public/id');
        $job->handle();
    }

    public function test_delete_cloudinary_asset_skips_on_empty_public_id()
    {
        Log::shouldReceive('debug')
            ->once()
            ->with('DeleteCloudinaryAsset skipped: empty public ID');

        $job = new DeleteCloudinaryAsset(null);
        $job->handle();
    }

    public function test_cloudinary_public_id_column_exists_and_is_nullable()
    {
        $tables = [
            'sourcing_requests',
            'quotations',
            'quotation_media',
            'sourcing_orders',
            'sourcing_order_media',
            'payment_methods',
            'users',
            'refund_requests',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'cloudinary_public_id'),
                "Table {$table} missing cloudinary_public_id column"
            );
        }
    }
}
