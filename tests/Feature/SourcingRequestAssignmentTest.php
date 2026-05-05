<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SourcingRequestAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Session::start();
    }

    /** @test */
    public function it_auto_assigns_admin_when_status_changes_to_in_review()
    {
        // 1. Arrange: Create Admin and Client
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        // Create necessary relations
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $service = Service::factory()->create();

        // Create a pending unassigned request
        $sourcingRequest = SourcingRequest::create([
            'user_id' => $client->id,
            'category_id' => $category->id,
            'product_name' => 'Test Product',
            'status' => 'pending',
            'assigned_to_admin_id' => null,
            'assigned_at' => null,
        ]);

        // Add destinations to make it valid if needed (though model create doesn't strictly enforce it unless validation layer)
        $sourcingRequest->destinations()->create([
            'country_id' => $country->id,
            'service_id' => $service->id,
            'quantity' => 10,
        ]);

        $this->assertNull($sourcingRequest->assigned_to_admin_id);

        // 2. Act: Admin changes status to 'in_review'
        // We simulate this via the controller updateStatus route or direct model update if testing independent of controller.
        // The requirement implies "when the admin changes the status", which usually happens via Controller -> Model.
        // Let's test via the endpoint to be sure the full flow works, or Model directly if we trust the Observer alone.
        // Let's test the Model update first to verify the Observer works. Since it's an observer test mainly.

        $this->actingAs($admin);

        // Use the route to simulate real user action
        $response = $this->put(route('admin.sourcing-requests.update-status', $sourcingRequest), [
            'status' => 'in_review',
        ]);

        // 3. Assert
        $sourcingRequest->refresh();

        $this->assertEquals('in_review', $sourcingRequest->status);
        $this->assertEquals($admin->id, $sourcingRequest->assigned_to_admin_id, 'The request should be assigned to the admin who reviewed it.');
        $this->assertNotNull($sourcingRequest->assigned_at);
    }
}
