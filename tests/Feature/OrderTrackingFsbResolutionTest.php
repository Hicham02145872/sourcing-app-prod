<?php

namespace Tests\Feature;

use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class OrderTrackingFsbResolutionTest extends TestCase
{
    use RefreshDatabase;

    protected function createOrder(array $overrides = []): SourcingOrder
    {
        $client = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $admin->id,
            'status' => 'quoted',
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'sent',
            'amount' => 200.00,
        ]);

        return SourcingOrder::factory()->create(array_merge([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'paid',
            'fsb_tracking_created_at' => now()->subHours(2),
        ], $overrides));
    }

    protected function resolveAlias(string $number): array
    {
        $method = new ReflectionMethod(UnifiedTrackingService::class, 'resolveAlias');
        $method->setAccessible(true);

        return $method->invoke(app(UnifiedTrackingService::class), $number, null);
    }

    public function test_tracking_du_fsb_sans_transporteur_retourne_le_statut_virtuel(): void
    {
        $order = $this->createOrder();

        $result = app(UnifiedTrackingService::class)->track($order->fsb_tracking_number);

        $this->assertSame(true, $result['success'] ?? false);
        $this->assertSame(true, $result['is_virtual'] ?? false);
        $this->assertSame('FSB', $result['provider'] ?? null);
        $this->assertSame($order->fsb_tracking_number, $result['tracking_number']);
        $this->assertContains($result['status'] ?? null, ['shipment_preparing', 'in_transit_china']);
    }

    public function test_alias_fsb_resout_le_numero_transporteur_reel(): void
    {
        $order = $this->createOrder([
            'tracking_number' => '1Z999AA10123456784',
            'tracking_carrier' => 'UPS',
            'real_tracking_assigned_at' => now(),
        ]);

        [$realNumber, $realCarrier, $isAlias, $error] = $this->resolveAlias($order->fsb_tracking_number);

        $this->assertNull($error);
        $this->assertTrue($isAlias);
        $this->assertSame('1Z999AA10123456784', $realNumber);
        $this->assertSame('UPS', $realCarrier);
    }

    public function test_le_numero_transporteur_reel_n_est_pas_ecrase_par_le_fsb(): void
    {
        $order = $this->createOrder([
            'tracking_number' => '1Z999AA10123456784',
            'tracking_carrier' => 'UPS',
            'real_tracking_assigned_at' => now(),
        ]);

        [$realNumber, , , $error] = $this->resolveAlias($order->fsb_tracking_number);

        $this->assertNull($error);
        $this->assertSame('1Z999AA10123456784', $realNumber);
        $this->assertNotSame($order->fsb_tracking_number, $realNumber);
        $this->assertSame('1Z999AA10123456784', $order->fresh()->tracking_number);
        $this->assertSame('UPS', $order->fresh()->tracking_carrier);
    }

    public function test_fsb_inconnu_retourne_une_erreur(): void
    {
        $result = app(UnifiedTrackingService::class)->track('FSB000002');

        $this->assertSame(false, $result['success'] ?? true);
        $this->assertArrayHasKey('error', $result);
    }
}