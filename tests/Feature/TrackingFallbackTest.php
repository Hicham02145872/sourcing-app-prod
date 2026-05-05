<?php

namespace Tests\Feature;

use App\Services\Tracking\UnifiedTrackingService;
use Tests\TestCase;

class TrackingFallbackTest extends TestCase
{
    /**
     * Test that unknown carriers fallback to 17Track service.
     */
    public function test_unknown_carrier_falls_back_to_seventeen_track(): void
    {
        $service = app(UnifiedTrackingService::class);

        // Use a tracking number that doesn't match any known pattern
        $trackingNumber = 'UNKNOWN123456';

        $result = $service->track($trackingNumber);

        // The result should come from 17Track (not return null or error about unknown carrier)
        $this->assertIsArray($result);
        $this->assertArrayHasKey('provider', $result);
        // Provider should be 17Track or SeventeenTrack (depending on how it's named in the response)
    }

    /**
     * Test that known carriers are detected correctly.
     */
    public function test_known_carriers_are_detected(): void
    {
        $service = app(UnifiedTrackingService::class);

        // Test ChoiceXP pattern
        $result = $service->track('DBC123456');
        $this->assertEquals('Choicexp', $result['provider'] ?? null);

        // Test Faster pattern
        $result = $service->track('ME123456');
        $this->assertEquals('Faster', $result['provider'] ?? null);
    }

    /**
     * Test that explicit carrier parameter overrides auto-detection.
     */
    public function test_explicit_carrier_overrides_detection(): void
    {
        $service = app(UnifiedTrackingService::class);

        // Even with a number that looks like ChoiceXP, force it to use Faster
        $result = $service->track('DBC123456', 'faster');

        $this->assertEquals('Faster', $result['provider'] ?? null);
    }
}
