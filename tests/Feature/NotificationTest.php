<?php

namespace Tests\Feature;

use App\Models\SourcingRequest;
use App\Models\User;
use App\Notifications\SourcingRequestStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_see_their_notifications()
    {
        // 1. Create a client
        $client = User::factory()->create([
            'role' => 'client',
        ]);

        // 2. Create a sourcing request owned by client
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'pending',
        ]);

        // 3. Send notification to client
        // We can manually create a database notification or send the actual notification
        $notification = new SourcingRequestStatusUpdated($sourcingRequest);
        $client->notify($notification);

        // 4. Act as client and fetch notifications
        $response = $this->actingAs($client)
            ->getJson(route('notifications.index'));

        // 5. Assert notification is present
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'notifications');
        $response->assertJsonPath('notifications.0.sourcing_request_id', $sourcingRequest->id);
    }

    public function test_admin_only_sees_assigned_notifications()
    {
        // 1. Create two admins
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        // 2. Create a sourcing request assigned to Admin 1
        $sourcingRequest = SourcingRequest::factory()->create([
            'assigned_to_admin_id' => $admin1->id,
        ]);

        // 3. Notify both admins (simulate a broadcast or manual notification,
        // effectively we just want to check if they *could* see it if it was in their DB notifications)
        // Note: In reality, we usually only notify the assigned admin, but let's say a system broadcast happened.
        // Or simpler: create a DB notification for both.

        $notification = new SourcingRequestStatusUpdated($sourcingRequest);
        $admin1->notify($notification);
        $admin2->notify($notification);

        // 4. Admin 1 should see it (assigned)
        $response1 = $this->actingAs($admin1)
            ->getJson(route('notifications.index'));

        $response1->assertStatus(200);
        $response1->assertJsonCount(1, 'notifications');

        // 5. Admin 2 should NOT see it (not assigned)
        // Wait, the logic in NotificationFilterTrait extracts IDs from the notification data
        // and checks if the user is assigned to those IDs.

        $response2 = $this->actingAs($admin2)
            ->getJson(route('notifications.index'));

        $response2->assertStatus(200);
        $response2->assertJsonCount(0, 'notifications');
    }
}
