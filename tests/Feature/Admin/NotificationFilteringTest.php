<?php

namespace Tests\Feature\Admin;

use App\Models\SourcingRequest;
use App\Models\User;
use App\Notifications\SourcingRequestCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_sees_all_notifications(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $admin->id,
        ]);

        $superAdmin->notify(new SourcingRequestCreated($request));

        $response = $this->actingAs($superAdmin)->getJson(route('notifications.index'));
        $response->assertStatus(200);
        $this->assertGreaterThan(0, $response->json('total_count'));
    }

    public function test_regular_admin_sees_only_assigned_notifications(): void
    {
        $adminA = User::factory()->create(['role' => 'admin']);
        $adminB = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        // Create request assigned to admin A
        $requestA = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $adminA->id,
        ]);

        // Notify admin A
        $adminA->notify(new SourcingRequestCreated($requestA));

        // Also notify admin B (simulating broadcast)
        $adminB->notify(new SourcingRequestCreated($requestA));

        // Admin A should see the notification (assigned to them)
        $responseA = $this->actingAs($adminA)->getJson(route('notifications.index'));
        $responseA->assertStatus(200);
        $this->assertGreaterThan(0, $responseA->json('total_count'));

        // Admin B should NOT see the notification (not assigned)
        $responseB = $this->actingAs($adminB)->getJson(route('notifications.index'));
        $responseB->assertStatus(200);
        $this->assertEquals(0, $responseB->json('total_count'));
    }

    public function test_notifications_transfer_after_reassignment(): void
    {
        $adminA = User::factory()->create(['role' => 'admin']);
        $adminB = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        // Create request assigned to admin A
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $adminA->id,
        ]);

        // Notify both admins
        $adminA->notify(new SourcingRequestCreated($request));
        $adminB->notify(new SourcingRequestCreated($request));

        // Admin A should see it
        $responseA1 = $this->actingAs($adminA)->getJson(route('notifications.index'));
        $this->assertGreaterThan(0, $responseA1->json('total_count'));

        // Admin B should not see it
        $responseB1 = $this->actingAs($adminB)->getJson(route('notifications.index'));
        $this->assertEquals(0, $responseB1->json('total_count'));

        // Reassign from admin A to admin B
        $request->update(['assigned_to_admin_id' => $adminB->id]);

        // Admin A should no longer see it
        $responseA2 = $this->actingAs($adminA)->getJson(route('notifications.index'));
        $this->assertEquals(0, $responseA2->json('total_count'));

        // Admin B should now see it
        $responseB2 = $this->actingAs($adminB)->getJson(route('notifications.index'));
        $this->assertGreaterThan(0, $responseB2->json('total_count'));
    }

    public function test_admin_does_not_see_unassigned_notifications(): void
    {
        $adminA = User::factory()->create(['role' => 'admin']);
        $adminB = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        // Create request assigned to admin A (not B)
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $adminA->id,
        ]);

        // Broadcast notification to both admins (simulating a mistake or broadcast scenario)
        $adminA->notify(new SourcingRequestCreated($request));
        $adminB->notify(new SourcingRequestCreated($request));

        // Admin A should see it (assigned)
        $responseA = $this->actingAs($adminA)->getJson(route('notifications.index'));
        $responseA->assertStatus(200);
        $this->assertGreaterThan(0, $responseA->json('total_count'));

        // Admin B should NOT see it (not assigned) - filter removes it
        $responseB = $this->actingAs($adminB)->getJson(route('notifications.index'));
        $responseB->assertStatus(200);
        $this->assertEquals(0, $responseB->json('total_count'));
    }

    public function test_notifications_without_entity_still_visible(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create a notification manually without entity ID
        \DB::table('notifications')->insert([
            'id' => \Str::uuid()->toString(),
            'type' => 'App\\Notifications\\SourcingRequestCreated',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $admin->id,
            'data' => json_encode([
                'title' => 'Test Notification',
                'body' => 'This is a test without entity ID',
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Admin should see it even without entity assignment
        $response = $this->actingAs($admin)->getJson(route('notifications.index'));
        $response->assertStatus(200);
        $this->assertGreaterThan(0, $response->json('total_count'));
    }

    public function test_reassignment_sends_notifications_to_both_admins(): void
    {
        $adminA = User::factory()->create(['role' => 'admin']);
        $adminB = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        // Create request assigned to admin A
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $adminA->id,
        ]);

        // Clear any auto-assignment notifications
        $adminA->notifications()->delete();
        $adminB->notifications()->delete();

        // Reassign from admin A to admin B (simulating super admin action)
        $request->update(['assigned_to_admin_id' => $adminB->id]);

        // Admin A should receive "assignment removed" notification
        $this->assertGreaterThan(0, $adminA->notifications()->count(), 'Admin A should receive a notification');
        $notificationA = $adminA->notifications()->first();
        $this->assertEquals('App\Notifications\DossierAssignmentRemoved', $notificationA->type);
        $this->assertEquals($request->id, $notificationA->data['sourcing_request_id']);

        // Admin B should receive "assigned to you" notification
        $this->assertGreaterThan(0, $adminB->notifications()->count(), 'Admin B should receive a notification');
        $notificationB = $adminB->notifications()->first();
        $this->assertEquals('App\Notifications\SourcingRequestAssigned', $notificationB->type);
        $this->assertEquals($request->id, $notificationB->data['sourcing_request_id']);
    }
}
