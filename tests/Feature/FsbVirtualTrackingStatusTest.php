<?php

namespace Tests\Feature;

use App\Models\SourcingOrder;
use App\Services\Tracking\VirtualTrackingStatusService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FsbVirtualTrackingStatusTest extends TestCase
{
    use RefreshDatabase;

    protected VirtualTrackingStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(VirtualTrackingStatusService::class);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Before 24h: status is "Shipping Preparing".
     */
    public function test_fsb_status_is_shipment_preparing_before_24_hours(): void
    {
        $asOf = Carbon::parse('2026-02-20 12:00:00');

        $order = SourcingOrder::factory()->create([
            'status' => 'paid',
            'tracking_number' => null,
            'real_tracking_assigned_at' => null,
            'fsb_tracking_created_at' => Carbon::parse('2026-02-19 13:00:00'), // 23 hours before $asOf
        ]);

        $status = $this->service->getVirtualStatus($order, $asOf);

        $this->assertSame('shipment_preparing', $status);
    }

    /**
     * After 24h: status changes to "In Transit China".
     */
    public function test_fsb_status_changes_to_in_transit_china_after_24_hours(): void
    {
        $asOf = Carbon::parse('2026-02-20 12:00:00');

        $order = SourcingOrder::factory()->create([
            'status' => 'paid',
            'tracking_number' => null,
            'real_tracking_assigned_at' => null,
            'fsb_tracking_created_at' => Carbon::parse('2026-02-19 11:00:00'), // 25 hours before $asOf
        ]);

        $status = $this->service->getVirtualStatus($order, $asOf);

        $this->assertSame('in_transit_china', $status);
    }

    /**
     * Exactly at 24h: status is "In Transit China" (>= 24h).
     */
    public function test_fsb_status_is_in_transit_china_at_exactly_24_hours(): void
    {
        $asOf = Carbon::parse('2026-02-20 12:00:00');

        $order = SourcingOrder::factory()->create([
            'status' => 'paid',
            'tracking_number' => null,
            'real_tracking_assigned_at' => null,
            'fsb_tracking_created_at' => Carbon::parse('2026-02-19 12:00:00'), // exactly 24 hours before $asOf
        ]);

        $status = $this->service->getVirtualStatus($order, $asOf);

        $this->assertSame('in_transit_china', $status);
    }

    /**
     * Full response before 24h contains "Shipping Preparing" and one event.
     */
    public function test_virtual_tracking_response_before_24h_has_shipment_preparing(): void
    {
        $asOf = Carbon::parse('2026-02-20 12:00:00');

        $order = SourcingOrder::factory()->create([
            'status' => 'paid',
            'tracking_number' => null,
            'real_tracking_assigned_at' => null,
            'fsb_tracking_created_at' => Carbon::parse('2026-02-20 00:00:00'), // 12 hours before $asOf
        ]);

        $response = $this->service->getVirtualTrackingResponse($order, $asOf);

        $this->assertTrue($response['success']);
        $this->assertSame('shipment_preparing', $response['status']);
        $this->assertCount(1, $response['events']);
        $this->assertSame('shipment_preparing', $response['events'][0]['status']);
    }

    /**
     * Full response after 24h contains "In Transit China" and two events.
     */
    public function test_virtual_tracking_response_after_24h_has_in_transit_china(): void
    {
        $asOf = Carbon::parse('2026-02-20 12:00:00');

        $order = SourcingOrder::factory()->create([
            'status' => 'paid',
            'tracking_number' => null,
            'real_tracking_assigned_at' => null,
            'fsb_tracking_created_at' => Carbon::parse('2026-02-19 11:00:00'), // 25 hours before $asOf
        ]);

        $response = $this->service->getVirtualTrackingResponse($order, $asOf);

        $this->assertTrue($response['success']);
        $this->assertSame('in_transit_china', $response['status']);
        $this->assertCount(2, $response['events']);
        $this->assertSame('shipment_preparing', $response['events'][0]['status']);
        $this->assertSame('in_transit_china', $response['events'][1]['status']);
    }
}
