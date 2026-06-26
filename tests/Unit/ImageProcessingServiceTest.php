<?php

namespace Tests\Unit;

use App\Services\ImageProcessingService;
use App\Services\ImageResult;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageProcessingServiceTest extends TestCase
{
    private ImageProcessingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ImageProcessingService::class);
    }

    public function test_returns_image_result_object()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $result = $this->service->compressAndStore($file, 'test-uploads', 'local');

        $this->assertInstanceOf(ImageResult::class, $result);
        $this->assertNotNull($result->path);
    }

    public function test_returns_cloudinary_result_when_configured()
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        $result = $this->service->compressAndStore($file, 'test-uploads', 'public');

        $this->assertInstanceOf(ImageResult::class, $result);
        $this->assertNotNull($result->path);

        if ($result->isCloudinary()) {
            $this->assertStringContainsString('cloudinary.com', $result->path);
            $this->assertNotNull($result->publicId);
        }
    }

    public function test_fallback_path_does_not_contain_cloudinary_when_not_configured()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $result = $this->service->compressAndStore($file, 'test-uploads', 'local');

        $this->assertStringNotContainsString('cloudinary.com', $result->path);
        $this->assertNull($result->publicId);
    }
}
