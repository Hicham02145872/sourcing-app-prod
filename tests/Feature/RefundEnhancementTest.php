<?php

namespace Tests\Feature;

use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use App\Models\User;
use App\Notifications\RefundStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RefundEnhancementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_client_can_only_view_their_own_refund_request()
    {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);
        $refund = RefundRequest::factory()->create(['user_id' => $client1->id]);

        $this->actingAs($client1)
            ->get(route('client.refund-requests.show', $refund))
            ->assertStatus(200);

        $this->actingAs($client2)
            ->get(route('client.refund-requests.show', $refund))
            ->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_view_assigned_or_unassigned_refund_requests()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $assignedRefund = RefundRequest::factory()->create(['assigned_to_admin_id' => $admin->id]);
        $unassignedRefund = RefundRequest::factory()->create(['assigned_to_admin_id' => null]);
        $otherAssignedRefund = RefundRequest::factory()->create(['assigned_to_admin_id' => $otherAdmin->id]);

        $this->actingAs($admin)
            ->get(route('admin.refund-requests.show', $assignedRefund))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.refund-requests.show', $unassignedRefund))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.refund-requests.show', $otherAssignedRefund))
            ->assertStatus(403);
    }

    /** @test */
    public function a_super_admin_can_view_all_refund_requests()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $refund = RefundRequest::factory()->create(['assigned_to_admin_id' => $otherAdmin->id]);

        $this->actingAs($superAdmin)
            ->get(route('admin.refund-requests.show', $refund))
            ->assertStatus(200);
    }

    /** @test */
    public function a_notification_is_sent_when_refund_status_is_updated()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $refund = RefundRequest::factory()->create(['assigned_to_admin_id' => $admin->id]);

        $this->actingAs($admin)
            ->patch(route('admin.refund-requests.update-status', $refund), [
                'status' => 'approved',
                'amount_approved' => 50,
                'admin_notes' => 'Testing notifications',
            ]);

        Notification::assertSentTo(
            $refund->user,
            RefundStatusUpdated::class
        );
    }

    /** @test */
    public function small_refunds_are_not_automatically_approved()
    {
        // config(['refunds.auto_approve_limit' => 20]); // Logic removed

        $client = User::factory()->create(['role' => 'client']);
        $order = SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'total_amount' => 100,
            'status' => 'delivered', // Status must be allowed for refund
        ]);

        $response = $this->actingAs($client)
            ->post(route('client.sourcing-orders.refund-request', $order), [
                'type' => 'partial',
                'amount_requested' => 15,
                'reason_category' => 'Test',
                'reason_description' => 'Automatically approve this',
            ]);

        $response->assertRedirect();

        $refund = RefundRequest::where('sourcing_order_id', $order->id)->first();
        $this->assertNotNull($refund, 'Refund request was not created');
        $this->assertEquals('pending', $refund->status); // Should remain pending
        $this->assertNull($refund->amount_approved);

        $order->refresh();
        $this->assertEquals('waiting_for_refund', $order->status);
    }

    /** @test */
    public function admin_refund_dashboard_can_be_filtered_by_status()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        RefundRequest::factory()->create(['status' => 'pending']);
        RefundRequest::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($admin)
            ->get(route('admin.refund-requests.index', ['status' => 'pending']));

        $response->assertStatus(200);

        $requests = $response->viewData('requests');
        $this->assertCount(1, $requests);
        $this->assertEquals('pending', $requests->first()->status);
    }
}
