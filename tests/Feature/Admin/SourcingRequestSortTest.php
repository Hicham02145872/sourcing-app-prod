<?php

namespace Tests\Feature\Admin;

use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourcingRequestSortTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_sourcing_requests_in_correct_order()
    {
        // 1. Create Admins
        $me = User::factory()->create(['role' => 'admin', 'name' => 'Me']);
        $otherAdmin = User::factory()->create(['role' => 'admin', 'name' => 'Other']);
        $client = User::factory()->create(['role' => 'client']); // Needed for sourcing request

        // 2. Create Sourcing Requests
        // Request Assigned to Me
        $assignedToMe = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $me->id,
            'status' => 'pending',
            'product_name' => 'Assigned To Me',
            'created_at' => now()->subMinutes(10), // Older
        ]);

        // Request Unassigned
        $unassigned = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => null,
            'status' => 'pending',
            'product_name' => 'Unassigned',
            'created_at' => now()->subMinutes(5), // Newer
        ]);

        // Request Assigned to Other
        $assignedToOther = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $otherAdmin->id,
            'status' => 'pending',
            'product_name' => 'Assigned To Other',
            'created_at' => now(), // Newest
        ]);

        // 3. Act as "Me" and visit the index page
        $this->actingAs($me);
        $response = $this->get(route('admin.sourcing-requests.index'));

        // 4. Assertions
        $response->assertStatus(200);

        // Get the IDs of the sourcing requests in the order they appear in the view
        $viewIds = $response->viewData('sourcingRequests')->pluck('id')->toArray();

        // Expected Order: Unassigned -> Assigned To Me -> Assigned To Other
        $expectedOrder = [$unassigned->id, $assignedToMe->id, $assignedToOther->id];

        $this->assertEquals($expectedOrder, $viewIds, 'Sourcing requests are not sorted correctly.');
    }
}
