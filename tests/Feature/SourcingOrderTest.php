<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SourcingOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Session::start();
    }

    /** @test */
    public function a_client_can_upload_a_proof_of_payment(): void
    {
        Storage::fake('local'); // Use the 'local' disk for testing

        // Arrange
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'accepted',
        ]);
        $sourcingOrder = SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'pending_payment',
        ]);

        $proofOfPayment = UploadedFile::fake()->image('proof.jpg');

        // Act
        $response = $this->actingAs($client)
                         ->post(route('client.sourcing-orders.upload-proof-of-payment', $sourcingOrder), [
                             'proof_of_payment' => $proofOfPayment,
                             '_token' => Session::token(),
                         ]);

        // Assert
        $response->assertRedirect(route('client.sourcing-orders.show', $sourcingOrder));
        $response->assertSessionHas('status', 'Proof of payment uploaded successfully. It will be reviewed by an admin.');

        // Assert that the file was stored on the 'local' disk
        Storage::disk('local')->assertExists('proofs_of_payment/' . $proofOfPayment->hashName());

        // Assert that the sourcing order was updated in the database
        $this->assertDatabaseHas('sourcing_orders', [
            'id' => $sourcingOrder->id,
            'proof_of_payment_path' => 'proofs_of_payment/' . $proofOfPayment->hashName(),
            'status' => 'paid',
        ]);
    }

    /** @test */
    public function an_admin_can_approve_proof_of_payment_and_change_order_status(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'accepted',
        ]);
        $sourcingOrder = SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'paid',
            'proof_of_payment_path' => 'proofs_of_payment/some_proof.jpg', // Assume a file exists
        ]);

        // Act
        $response = $this->actingAs($admin)
                         ->patch(route('admin.sourcing-orders.update-status', $sourcingOrder), [
                             'status' => 'shipped',
                             '_token' => Session::token(),
                         ]);

        // Assert
        $response->assertRedirect(); // Should redirect back
        $response->assertSessionHas('status', 'Sourcing order status updated successfully!');

        $this->assertDatabaseHas('sourcing_orders', [
            'id' => $sourcingOrder->id,
            'status' => 'shipped',
        ]);
    }
}
