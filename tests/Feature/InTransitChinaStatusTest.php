<?php

namespace Tests\Feature;

use App\Livewire\Admin\SourcingOrderWorkflow;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class InTransitChinaStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function paidOrder(User $client, User $admin): SourcingOrder
    {
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now(),
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $request->id,
            'status' => 'accepted',
        ]);

        return SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'sourcing_request_id' => $request->id,
            'status' => 'paid',
            'assigned_to_admin_id' => $admin->id,
        ]);
    }

    /** @test */
    public function updating_order_status_to_in_transit_china_reveals_the_evidence_zone(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->set('status', 'in_transit_china')
            ->call('updateStatus')
            ->assertDispatched('show-success-toast')
            ->assertSet('showEvidenceZone', true)
            ->assertSet('status', 'in_transit_china');

        $this->assertSame('in_transit_china', $order->fresh()->status);
    }

    /** @test */
    public function admin_can_save_china_tracking_and_label_photo_as_evidence(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);
        $order->update(['status' => 'in_transit_china']);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->set('chinaTrackingNumber', 'LP1234567890')
            ->set('tracking_number', 'ME49508327')
            ->set('tracking_carrier', 'Faster.ae')
            ->set('packageLabelPhoto', UploadedFile::fake()->image('label.jpg'))
            ->call('saveChinaTransitEvidence')
            ->assertHasNoErrors()
            ->assertDispatched('show-success-toast');

        $order->refresh();
        $this->assertSame('LP1234567890', $order->china_tracking_number);
        $this->assertSame('ME49508327', $order->tracking_number);
        $this->assertSame('Faster.ae', $order->tracking_carrier);
        $this->assertNotNull($order->package_label_photo_path);
        $this->assertNotNull($order->real_tracking_assigned_at);
        Storage::disk('public')->assertExists($order->package_label_photo_path);
    }

    /** @test */
    public function invalid_photo_is_rejected_with_validation_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);
        $order->update(['status' => 'in_transit_china']);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->set('chinaTrackingNumber', 'LP1234567890')
            ->set('packageLabelPhoto', UploadedFile::fake()->create('document.txt', 1))
            ->call('saveChinaTransitEvidence')
            ->assertHasErrors(['packageLabelPhoto']);

        $order->refresh();
        $this->assertNull($order->china_tracking_number);
        $this->assertNull($order->package_label_photo_path);
        $this->assertEmpty(Storage::disk('public')->allFiles('sourcing'));
    }

    /** @test */
    public function evidence_can_be_saved_without_photo_when_china_tracking_known(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);
        $order->update(['status' => 'in_transit_china']);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->set('chinaTrackingNumber', 'LP9876543210')
            ->call('saveChinaTransitEvidence')
            ->assertHasNoErrors()
            ->assertDispatched('show-success-toast');

        $order->refresh();
        $this->assertSame('LP9876543210', $order->china_tracking_number);
        $this->assertNull($order->package_label_photo_path);
    }

    /** @test */
    public function non_assigned_admin_cannot_save_evidence(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);
        $order->update(['status' => 'in_transit_china']);

        Livewire::actingAs($otherAdmin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->set('chinaTrackingNumber', 'LP1234567890')
            ->call('saveChinaTransitEvidence');

        $order->refresh();
        $this->assertNull($order->china_tracking_number);
    }

    /** @test */
    public function super_admin_can_save_evidence_on_any_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);
        $order->update(['status' => 'in_transit_china']);

        Livewire::actingAs($superAdmin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->set('chinaTrackingNumber', 'LP555')
            ->set('packageLabelPhoto', UploadedFile::fake()->image('label.jpg'))
            ->call('saveChinaTransitEvidence')
            ->assertHasNoErrors()
            ->assertDispatched('show-success-toast');

        $order->refresh();
        $this->assertSame('LP555', $order->china_tracking_number);
        $this->assertNotNull($order->package_label_photo_path);
    }

    /** @test */
    public function evidence_zone_is_visible_only_for_in_transit_and_later_statuses(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->assertSet('showEvidenceZone', false);

        $order->update(['status' => 'in_transit_china']);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->assertSet('showEvidenceZone', true);

        $order->update(['status' => 'arrival_uae']);

        Livewire::actingAs($admin)
            ->test(SourcingOrderWorkflow::class, ['sourcingOrder' => $order])
            ->assertSet('showEvidenceZone', true);
    }

    /** @test */
    public function client_never_sees_evidence_from_their_sourcing_request_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->paidOrder($client, $admin);
        $request = $order->sourcingRequest;

        $order->update([
            'status' => 'in_transit_china',
            'china_tracking_number' => 'LP1234567890',
        ]);

        $this->actingAs($client)
            ->get(route('client.sourcing-requests.show', $request))
            ->assertOk()
            ->assertDontSee('LP1234567890');
    }
}
