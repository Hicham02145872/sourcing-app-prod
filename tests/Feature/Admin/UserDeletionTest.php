<?php

namespace Tests\Feature\Admin;

use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user to perform the deletion
        $this->adminUser = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_delete_client_and_related_data_is_cascaded(): void
    {
        // Create a client user
        $clientUser = User::factory()->create(['role' => 'client']);

        // Create related data for the client
        $sourcingRequest = SourcingRequest::factory()->create(['user_id' => $clientUser->id]);
        $quotation = Quotation::factory()->create(['sourcing_request_id' => $sourcingRequest->id]);
        $sourcingOrder = SourcingOrder::factory()->create([
            'user_id' => $clientUser->id,
            'quotation_id' => $quotation->id,
        ]);

        // Assert that the client and their data exist
        $this->assertDatabaseHas('users', ['id' => $clientUser->id]);
        $this->assertDatabaseHas('sourcing_requests', ['id' => $sourcingRequest->id]);
        $this->assertDatabaseHas('quotations', ['id' => $quotation->id]);
        $this->assertDatabaseHas('sourcing_orders', ['id' => $sourcingOrder->id]);

        // Act as admin and delete the client
        $response = $this->actingAs($this->adminUser)->delete(route('admin.users.destroy', $clientUser));

        // Assert redirect and session message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'User deleted successfully.');

        // Assert that the client and all related data are deleted
        $this->assertDatabaseMissing('users', ['id' => $clientUser->id]);
        $this->assertDatabaseMissing('sourcing_requests', ['id' => $sourcingRequest->id]);
        $this->assertDatabaseMissing('quotations', ['id' => $quotation->id]);
        $this->assertDatabaseMissing('sourcing_orders', ['id' => $sourcingOrder->id]);
    }

    public function test_admin_cannot_delete_super_admin(): void
    {
        $superAdminUser = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.users.destroy', $superAdminUser));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete a Super Admin.');
        $this->assertDatabaseHas('users', ['id' => $superAdminUser->id]); // Super admin should still exist
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.users.destroy', $this->adminUser));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You cannot delete your own account.');
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]); // Admin should still exist
    }
}
