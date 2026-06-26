<?php

namespace Tests\Unit;

use App\Jobs\DeleteCloudinaryAsset;
use Tests\TestCase;

class DeleteCloudinaryAssetJobTest extends TestCase
{
    public function test_handles_null_public_id_gracefully()
    {
        $job = new DeleteCloudinaryAsset(null);
        $job->handle();

        $this->assertTrue(true);
    }

    public function test_skips_when_cloudinary_not_configured()
    {
        config(['cloudinary.cloud_url' => null]);
        putenv('CLOUDINARY_URL=');

        $job = new DeleteCloudinaryAsset('test-public-id');
        $job->handle();

        $this->assertTrue(true);
    }

    public function test_backoff_returns_expected_delays()
    {
        $job = new DeleteCloudinaryAsset('test-public-id');

        $backoff = $job->backoff();

        $this->assertEquals([30, 120, 300], $backoff);
    }
}
