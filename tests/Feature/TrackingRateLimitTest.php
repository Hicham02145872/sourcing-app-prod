<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingRateLimitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that tracking endpoint is rate limited.
     */
    public function test_tracking_endpoint_is_rate_limited(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $this->actingAs($user);

        // Make 10 requests (within limit)
        for ($i = 0; $i < 10; $i++) {
            $response = $this->get(route('client.tracking.data', ['number' => 'TEST123']));
            $this->assertNotEquals(429, $response->status(), "Request {$i} should not be rate limited");
        }

        // 11th request should be rate limited
        $response = $this->get(route('client.tracking.data', ['number' => 'TEST123']));
        $this->assertEquals(429, $response->status(), '11th request should be rate limited');
    }

    /**
     * Test that 17Track endpoint is also rate limited.
     */
    public function test_seventeen_track_endpoint_is_rate_limited(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $this->actingAs($user);

        // Make 10 requests (within limit)
        for ($i = 0; $i < 10; $i++) {
            $response = $this->get(route('client.tracking.17track.data', ['number' => 'TEST123']));
            $this->assertNotEquals(429, $response->status());
        }

        // 11th request should be rate limited
        $response = $this->get(route('client.tracking.17track.data', ['number' => 'TEST123']));
        $this->assertEquals(429, $response->status());
    }
}
