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

class QuotationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Session::start();
    }

    /** @test */
    public function an_admin_can_create_a_quotation_for_a_sourcing_request(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $service = Service::factory()->create();

        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'category_id' => $category->id,
            'status' => 'in_review',
        ]);
        $sourcingRequest->destinations()->create([
            'country_id' => $country->id,
            'service_id' => $service->id,
            'quantity' => 100,
            'address' => 'Test Address',
        ]);

        $quotationData = [
            'sourcing_request_id' => $sourcingRequest->id,
            'unit_price' => 100.50,
            'commission_service' => 10.00,
            'unit_weight' => 5.20,
            'weight_unit' => 'kg',
            'delivery_cost_china' => 25.00,
            'currency' => 'USD',
            '_token' => Session::token(),
        ];

        // Act
        $response = $this->actingAs($admin)
            ->post(route('admin.quotations.store'), $quotationData);

        // Assert
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('status', 'Quotation created successfully!');

        $this->assertDatabaseHas('quotations', [
            'sourcing_request_id' => $sourcingRequest->id,
            'unit_price' => 100.50,
            'commission_service' => 10.00,
            'unit_weight' => 5.20,
            'delivery_cost_china' => 25.00,
            'currency' => 'USD',
            'amount' => 10085, // (100.50 * 100) + 10.00 + 25.00
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function a_client_can_accept_a_quotation_which_creates_an_order(): void
    {
        // Arrange
        $client = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']); // Admin to create the quotation
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'quoted',
        ]);
        $quotation = \App\Models\Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'pending', // Or 'sent'
        ]);

        // Act
        $response = $this->actingAs($client)
            ->post(route('client.quotations.accept', $quotation), ['_token' => Session::token()]);

        // Assert
        $sourcingOrder = \App\Models\SourcingOrder::where('quotation_id', $quotation->id)->first();
        $this->assertNotNull($sourcingOrder);

        $response->assertRedirect(route('client.sourcing-orders.show', $sourcingOrder));
        $response->assertSessionHas('status', 'Quotation accepted successfully! A pro-forma invoice has been sent to your email.');

        $this->assertDatabaseHas('sourcing_orders', [
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'pending_payment',
        ]);

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('sourcing_requests', [
            'id' => $sourcingRequest->id,
            'status' => 'accepted',
        ]);
    }

    /** @test */
    public function accepting_an_already_accepted_quotation_redirects_without_error(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
        ]);
        $quotation = \App\Models\Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'accepted',
        ]);
        $order = \App\Models\SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'pending_payment',
        ]);

        $response = $this->actingAs($client)
            ->post(route('client.quotations.accept', $quotation), ['_token' => Session::token()]);

        $response->assertRedirect(route('client.sourcing-orders.show', $order));
        $response->assertSessionHas('status', __('This quotation has already been accepted.'));
    }

    /** @test */
    public function accepting_when_sourcing_request_is_already_accepted_does_not_throw_transition_exception(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
        ]);
        $quotation = \App\Models\Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'sent',
        ]);
        $order = \App\Models\SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'pending_payment',
        ]);

        $response = $this->actingAs($client)
            ->post(route('client.quotations.accept', $quotation), ['_token' => Session::token()]);

        $response->assertRedirect(route('client.sourcing-orders.show', $order));
        $response->assertSessionHas('status', __('This quotation has already been accepted.'));
    }
}
