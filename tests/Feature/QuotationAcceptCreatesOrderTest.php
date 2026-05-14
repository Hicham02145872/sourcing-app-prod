<?php

namespace Tests\Feature;

use App\Http\Controllers\Client\QuotationController;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class QuotationAcceptCreatesOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_accept_creates_order_with_sourcing_request_and_assigned_admin()
    {
        // Arrange
        $client = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $admin->id,
            'status' => 'quoted',
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'sent',
            'amount' => 150.00,
        ]);

        $this->actingAs($client);

        // Act
        $controller = app(QuotationController::class);
        $request = Request::create('/','POST', []);
        $response = $controller->accept($request, 'en', $quotation);

        // Assert
        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => 'accepted',
        ]);

        $order = SourcingOrder::where('quotation_id', $quotation->id)->first();
        $this->assertNotNull($order, 'SourcingOrder was not created');
        $this->assertEquals($sourcingRequest->id, $order->sourcing_request_id);
        $this->assertEquals($admin->id, $order->assigned_to_admin_id);
    }
}
