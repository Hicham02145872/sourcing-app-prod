<?php

namespace Tests\Feature;

use App\Livewire\Admin\SourcingRequestWorkflow;
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

    private function acceptedRequestWithOrder(User $client, User $admin): SourcingOrder
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
        ])->load('sourcingRequest');
    }

    /** @test */
    public function admin_can_mark_in_transit_with_valid_data_and_order_is_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->acceptedRequestWithOrder($client, $admin);
        $request = $order->sourcingRequest;

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $request])
            ->set('chinaTrackingNumber', 'LP1234567890')
            ->set('packageLabelPhoto', UploadedFile::fake()->image('label.jpg'))
            ->call('markInTransit')
            ->assertDispatched('show-success-toast');

        $this->assertSame('in_transit_china', $request->fresh()->status);

        $order->refresh();
        $this->assertSame('LP1234567890', $order->china_tracking_number);
        $this->assertNotNull($order->package_label_photo_path);
        $this->assertSame('in_transit_china', $order->status);
        Storage::disk('public')->assertExists($order->package_label_photo_path);
    }

    /** @test */
    public function invalid_photo_is_rejected_with_validation_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->acceptedRequestWithOrder($client, $admin);
        $request = $order->sourcingRequest;

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $request])
            ->set('chinaTrackingNumber', 'LP1234567890')
            ->set('packageLabelPhoto', UploadedFile::fake()->create('document.txt', 1))
            ->call('markInTransit')
            ->assertHasErrors(['packageLabelPhoto']);

        $this->assertSame('accepted', $request->fresh()->status);
        $order->refresh();
        $this->assertNull($order->china_tracking_number);
        $this->assertNull($order->package_label_photo_path);
        $this->assertEmpty(Storage::disk('public')->allFiles('sourcing'));
    }

    /** @test */
    public function unauthorized_transition_is_refused_and_status_unchanged(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'pending',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now(),
        ]);

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $request])
            ->set('chinaTrackingNumber', 'LP123')
            ->set('packageLabelPhoto', UploadedFile::fake()->image('label.jpg'))
            ->call('markInTransit')
            ->assertDispatched('show-error-toast');

        $this->assertSame('pending', $request->fresh()->status);

        $clientRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now(),
        ]);

        Livewire::actingAs($client)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $clientRequest])
            ->set('chinaTrackingNumber', 'LP456')
            ->set('packageLabelPhoto', UploadedFile::fake()->image('label2.jpg'))
            ->call('markInTransit')
            ->assertDispatched('show-error-toast');

        $this->assertSame('accepted', $clientRequest->fresh()->status);
    }

    /** @test */
    public function client_sees_phase_without_internal_details_and_super_admin_sees_them(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->acceptedRequestWithOrder($client, $admin);
        $request = $order->sourcingRequest;

        // Transition request to in_transit_china
        $request->status = 'in_transit_china';
        $request->save();

        $order->update([
            'china_tracking_number' => 'LP1234567890',
            'package_label_photo_path' => 'sourcing/in-transit/label.jpg',
        ]);

        // Fake a photo file so media_url resolves without storage errors
        \Illuminate\Support\Facades\Storage::disk('public')->put('sourcing/in-transit/label.jpg', 'fake');

        $this->actingAs($client)
            ->get(route('client.sourcing-requests.show', $request))
            ->assertOk()
            ->assertSee('In Transit (China)')
            ->assertDontSee('LP1234567890')
            ->assertDontSee('sourcing/in-transit/label.jpg');

        $this->actingAs($superAdmin)
            ->get(route('admin.sourcing-requests.show', $request))
            ->assertOk()
            ->assertSee('LP1234567890')
            ->assertSee('sourcing/in-transit/label.jpg');

        $order->update(['package_label_photo_path' => null]);
        $this->actingAs($superAdmin)
            ->get(route('admin.sourcing-requests.show', $request))
            ->assertOk()
            ->assertSee('LP1234567890')
            ->assertDontSee('sourcing/in-transit/label.jpg');
    }

    /** @test */
    public function existing_order_transitions_and_request_flows_remain_unchanged(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now(),
        ]);

        $order = SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'sourcing_request_id' => $request->id,
            'status' => 'shipment_preparing',
        ]);

        $this->assertTrue($order->canTransitionTo('in_transit_china'));
        $this->assertTrue($request->canTransitionTo('in_transit_china', $admin));

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $request])
            ->call('updateStatus', 'in_transit_china')
            ->assertDispatched('show-error-toast');

        $this->assertSame('accepted', $request->fresh()->status);

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $request])
            ->call('updateStatus', 'completed')
            ->assertDispatched('show-success-toast');

        $this->assertSame('completed', $request->fresh()->status);
    }
}