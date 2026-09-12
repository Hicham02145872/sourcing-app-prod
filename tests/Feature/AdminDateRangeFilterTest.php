<?php

namespace Tests\Feature;

use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDateRangeFilterTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin']);
    }

    /** @test */
    public function sourcing_requests_index_is_filtered_by_date_range(): void
    {
        $admin = $this->admin();
        $client = User::factory()->create(['role' => 'client']);
        $recent = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'pending',
            'created_at' => now()->subDays(1),
        ]);
        $old = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'pending',
            'created_at' => now()->subDays(10),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-requests.index', [
                'status' => 'pending',
                'date_debut' => now()->subDays(3)->toDateString(),
            ]))
            ->assertOk()
            ->assertSee($recent->product_name)
            ->assertDontSee($old->product_name);
    }

    /** @test */
    public function sourcing_orders_index_is_filtered_by_date_range(): void
    {
        $admin = $this->admin();
        $clientRecent = User::factory()->create(['role' => 'client', 'name' => 'Recent Order Client']);
        $clientOld = User::factory()->create(['role' => 'client', 'name' => 'Old Order Client']);

        SourcingOrder::factory()->create([
            'user_id' => $clientRecent->id,
            'status' => 'pending_payment',
            'created_at' => now()->subDays(1),
        ]);
        SourcingOrder::factory()->create([
            'user_id' => $clientOld->id,
            'status' => 'pending_payment',
            'created_at' => now()->subDays(10),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index', [
                'status' => 'pending_payment',
                'date_debut' => now()->subDays(3)->toDateString(),
                'date_fin' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('Recent Order Client')
            ->assertDontSee('Old Order Client');
    }

    /** @test */
    public function quotations_index_is_filtered_by_date_range(): void
    {
        $admin = $this->admin();
        $client = User::factory()->create(['role' => 'client']);
        $recentRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'product_name' => 'Recent Quotation Product',
        ]);
        $oldRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'product_name' => 'Old Quotation Product',
        ]);

        Quotation::factory()->create([
            'sourcing_request_id' => $recentRequest->id,
            'created_at' => now()->subDays(1),
        ]);
        Quotation::factory()->create([
            'sourcing_request_id' => $oldRequest->id,
            'created_at' => now()->subDays(10),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.quotations.index', [
                'date_debut' => now()->subDays(3)->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('Recent Quotation Product')
            ->assertDontSee('Old Quotation Product');
    }

    /** @test */
    public function users_index_is_filtered_by_date_range(): void
    {
        $admin = $this->admin();
        User::factory()->create(['role' => 'client', 'name' => 'Recent Joined Client', 'created_at' => now()->subDays(1)]);
        User::factory()->create(['role' => 'client', 'name' => 'Old Joined Client', 'created_at' => now()->subDays(10)]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', [
                'date_debut' => now()->subDays(3)->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('Recent Joined Client')
            ->assertDontSee('Old Joined Client');
    }
}
