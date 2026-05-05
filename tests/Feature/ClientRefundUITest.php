<?php

namespace Tests\Feature;

use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientRefundUITest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_refund_index_page_loads_with_correct_sections()
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)
            ->get(route('client.refund-requests.index'));

        $response->assertStatus(200);
        $response->assertSee('My Refund Claims');
        $response->assertSee('Eligible Orders');
        $response->assertSee('Dashboard'); // Breadcrumb check
        $response->assertSee('Refunds'); // Breadcrumb check
    }

    /** @test */
    public function client_refund_create_page_has_breadcrumbs_and_cancel_button()
    {
        $client = User::factory()->create(['role' => 'client']);
        $order = SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($client)
            ->get(route('client.refund-requests.create', $order));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Refunds');
        $response->assertSee('New Claim');
        $response->assertSee(route('client.refund-requests.index')); // Cancel button link
    }

    /** @test */
    public function client_refund_show_page_has_correct_navigation()
    {
        $client = User::factory()->create(['role' => 'client']);
        $refund = RefundRequest::factory()->create(['user_id' => $client->id]);

        $response = $this->actingAs($client)
            ->get(route('client.refund-requests.show', $refund));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Refunds');
        $response->assertSee("#{$refund->id}");
        $response->assertSee('Back to List');
        $response->assertSee(route('client.refund-requests.index')); // Back button link
    }
}
