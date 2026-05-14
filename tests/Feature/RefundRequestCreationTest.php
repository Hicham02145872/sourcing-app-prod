<?php

namespace Tests\Feature;

use App\Http\Controllers\Client\RefundRequestController;
use App\Models\Quotation;
use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class RefundRequestCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_refund_with_sourcing_request_and_assigned_admin()
    {
        $client = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $sourcingRequest = SourcingRequest::factory()->create([
            'assigned_to_admin_id' => $admin->id,
            'user_id' => $client->id,
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'amount' => 200.00,
        ]);

        $order = SourcingOrder::factory()->create([
            'quotation_id' => $quotation->id,
            'sourcing_request_id' => $sourcingRequest->id,
            'assigned_to_admin_id' => $admin->id,
            'status' => 'delivered',
            'user_id' => $client->id,
        ]);

        $this->actingAs($client);

        $controller = app(RefundRequestController::class);

        $req = Request::create('/', 'POST', [
            'type' => 'full',
            'damaged_quantity' => 1,
            'reason_category' => 'damaged',
            'reason_description' => 'Item arrived broken',
        ]);

        $response = $controller->store($req, 'en', $order);

        $this->assertDatabaseHas('refund_requests', [
            'sourcing_order_id' => $order->id,
            'sourcing_request_id' => $sourcingRequest->id,
            'assigned_to_admin_id' => $admin->id,
        ]);

        $refund = RefundRequest::where('sourcing_order_id', $order->id)->first();
        $this->assertNotNull($refund);
        $this->assertEquals($sourcingRequest->id, $refund->sourcing_request_id);
    }
}
