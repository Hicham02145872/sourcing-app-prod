<?php

namespace Tests\Feature;

use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TrackingCacheTest extends TestCase
{
    /**
     * Test that tracking results are cached.
     */
    public function test_tracking_results_are_cached(): void
    {
        Cache::flush();

        $trackingNumber = 'TEST123';
        $service = app(UnifiedTrackingService::class);

        // First call - should hit the service
        $result1 = $service->track($trackingNumber);

        // Verify cache was set
        $this->assertTrue(Cache::has("tracking:{$trackingNumber}"));

        // Second call - should hit cache
        $result2 = $service->track($trackingNumber);

        // Results should be identical
        $this->assertEquals($result1, $result2);
    }

    /**
     * Test that cache expires after configured TTL.
     */
    public function test_cache_expires_after_ttl(): void
    {
        Cache::flush();
        config(['tracking.cache_ttl' => 0]); // Set to 0 minutes for testing

        $trackingNumber = 'TEST456';
        $service = app(UnifiedTrackingService::class);

        $service->track($trackingNumber);

        // Cache should be expired immediately
        $this->assertFalse(Cache::has("tracking:{$trackingNumber}"));
    }

    /**
     * Test that different tracking numbers have separate cache entries.
     */
    public function test_different_tracking_numbers_have_separate_cache(): void
    {
        Cache::flush();

        $service = app(UnifiedTrackingService::class);

        $service->track('NUMBER1');
        $service->track('NUMBER2');

        $this->assertTrue(Cache::has('tracking:NUMBER1'));
        $this->assertTrue(Cache::has('tracking:NUMBER2'));
    }
}
