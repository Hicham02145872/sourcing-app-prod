<?php

namespace Tests\Feature;

use App\Models\FeatureFlag;
use App\Models\SourcingOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlaChinaEvidenceRuleTest extends TestCase
{
    use RefreshDatabase;

    private function enableSlaFlag(): void
    {
        FeatureFlag::create([
            'key' => 'sla_deadlines_autolock',
            'name' => 'SLA deadlines autolock',
            'status' => 'visible',
        ]);
    }

    private function restrictChinaOrder(User $admin, array $overrides = []): SourcingOrder
    {
        return SourcingOrder::factory()->create(array_merge([
            'assigned_to_admin_id' => $admin->id,
            'status' => 'in_transit_china',
            'status_changed_at' => now()->subDays(2),
            'is_restricted_due_to_delay' => true,
        ], $overrides));
    }

    /** @test */
    public function advancing_to_the_next_status_without_evidence_is_refused_while_restricted(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictChinaOrder($admin);

        $this->actingAs($admin)
            ->patch(route('admin.sourcing-orders.update-status', $order), [
                'status' => 'arrival_uae',
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame('in_transit_china', $order->fresh()->status);
        $this->assertTrue($order->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function the_next_status_is_allowed_once_evidence_is_uploaded(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictChinaOrder($admin, [
            'tracking_number' => 'ME123456789CN',
            'parcel_photo_path' => 'parcel-photos/parcel.png',
        ]);

        // Completing the evidence unlocks the order.
        $order->refresh();
        $this->assertFalse($order->is_restricted_due_to_delay);

        $this->actingAs($admin)
            ->patch(route('admin.sourcing-orders.update-status', $order), [
                'status' => 'arrival_uae',
            ])
            ->assertSessionHas('status');

        $this->assertSame('arrival_uae', $order->fresh()->status);
    }

    /** @test */
    public function saving_china_tracking_and_parcel_photo_unlocks_the_restricted_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictChinaOrder($admin);

        $order->update(['tracking_number' => 'ME123456789CN']);
        $this->assertTrue($order->fresh()->is_restricted_due_to_delay);

        $order->update(['parcel_photo_path' => 'parcel-photos/parcel.png']);
        $this->assertFalse($order->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function the_deadline_check_skips_in_transit_orders_with_full_evidence(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictChinaOrder($admin, [
            'is_restricted_due_to_delay' => false,
            'tracking_number' => 'ME123456789CN',
            'parcel_photo_path' => 'parcel-photos/parcel.png',
        ]);

        $this->artisan('workflow:check-deadlines')->assertSuccessful();

        $this->assertFalse($order->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function the_deadline_check_still_flags_in_transit_orders_without_evidence(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictChinaOrder($admin, ['is_restricted_due_to_delay' => false]);

        $this->artisan('workflow:check-deadlines')->assertSuccessful();

        $this->assertTrue($order->fresh()->is_restricted_due_to_delay);
    }
}
