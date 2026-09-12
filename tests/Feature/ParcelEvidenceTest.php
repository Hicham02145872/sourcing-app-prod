<?php

namespace Tests\Feature;

use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Notifications\ParcelEvidenceAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ParcelEvidenceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Session::start();
    }

    private function makeOrder(User $client): SourcingOrder
    {
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'accepted',
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'accepted',
        ]);

        return SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'paid',
        ]);
    }

    /** @test */
    public function admin_can_upload_parcel_photo_and_weight_and_client_is_notified(): void
    {
        Storage::fake('public');

        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->makeOrder($client);

        $photo = UploadedFile::fake()->image('parcel.jpg');

        $response = $this->actingAs($admin)
            ->post(route('admin.sourcing-orders.parcel.store', $order), [
                'parcel_photo' => $photo,
                'parcel_weight_kg' => '12.50',
                '_token' => Session::token(),
            ]);

        $response->assertSessionHas('status');

        $order->refresh();
        $this->assertNotNull($order->parcel_photo_path);
        $this->assertEquals('12.50', (string) $order->parcel_weight_kg);
        $this->assertNotNull($order->parcel_photo_uploaded_at);

        Storage::disk('public')->assertExists($order->parcel_photo_path);

        Notification::assertSentTo($client, ParcelEvidenceAdded::class);

        $serialized = $order->toArray();
        $this->assertArrayNotHasKey('parcel_weight_kg', $serialized);
        $this->assertArrayHasKey('parcel_photo_path', $serialized);
    }

    /** @test */
    public function replacing_parcel_photo_deletes_previous_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->makeOrder($client);

        $first = UploadedFile::fake()->image('first.jpg');
        $this->actingAs($admin)
            ->post(route('admin.sourcing-orders.parcel.store', $order), [
                'parcel_photo' => $first,
                'parcel_weight_kg' => '10',
                '_token' => Session::token(),
            ]);

        $firstPath = $order->refresh()->parcel_photo_path;

        $second = UploadedFile::fake()->image('second.jpg');
        $this->actingAs($admin)
            ->post(route('admin.sourcing-orders.parcel.store', $order), [
                'parcel_photo' => $second,
                'parcel_weight_kg' => '14',
                '_token' => Session::token(),
            ]);

        $order->refresh();
        $this->assertNotSame($firstPath, $order->parcel_photo_path);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($order->parcel_photo_path);
    }
}
