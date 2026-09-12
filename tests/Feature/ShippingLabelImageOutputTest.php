<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\FeatureFlag;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Services\ShippingLabelImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingLabelImageOutputTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $client;

    private SourcingOrder $order;

    private SourcingRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->client = User::factory()->create(['role' => 'client']);

        $this->request = SourcingRequest::factory()->create([
            'user_id' => $this->client->id,
            'status' => 'accepted',
            'product_name' => 'Test Product',
        ]);

        $country = Country::factory()->create(['name' => 'United Arab Emirates']);
        $service = Service::factory()->create(['name' => 'Door to Door']);

        $this->request->destinations()->create([
            'country_id' => $country->id,
            'service_id' => $service->id,
            'quantity' => 10,
            'address' => '123 Sheikh Zayed Road, Dubai, UAE',
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $this->request->id,
            'status' => 'accepted',
        ]);

        $this->order = SourcingOrder::factory()->create([
            'user_id' => $this->client->id,
            'quotation_id' => $quotation->id,
            'sourcing_request_id' => $this->request->id,
            'status' => 'paid',
        ]);
    }

    public function test_service_produces_png_signature_at_a4_300_dpi(): void
    {
        $service = app(ShippingLabelImageService::class);

        $blob = $service->pngBlob($service->render($this->order));

        $this->assertSame("\x89PNG\r\n\x1a\n", substr($blob, 0, 8));

        $info = getimagesizefromstring($blob);
        $this->assertNotFalse($info);
        $this->assertSame(2480, $info[0]);
        $this->assertSame(3508, $info[1]);
    }

    public function test_service_rendered_bytes_differ_between_orders(): void
    {
        $service = app(ShippingLabelImageService::class);

        $otherRequest = SourcingRequest::factory()->create([
            'user_id' => $this->client->id,
            'status' => 'accepted',
            'product_name' => 'Another Product',
        ]);
        $otherDestination = Country::factory()->create(['name' => 'Saudi Arabia']);
        $otherService = Service::factory()->create(['name' => 'Air Express']);
        $otherRequest->destinations()->create([
            'country_id' => $otherDestination->id,
            'service_id' => $otherService->id,
            'quantity' => 2,
            'address' => 'King Fahd Road, Riyadh',
        ]);
        $otherQuotation = Quotation::factory()->create([
            'sourcing_request_id' => $otherRequest->id,
            'status' => 'accepted',
        ]);
        $otherOrder = SourcingOrder::factory()->create([
            'user_id' => $this->client->id,
            'quotation_id' => $otherQuotation->id,
            'sourcing_request_id' => $otherRequest->id,
            'status' => 'paid',
            'tracking_number' => 'FSB000099',
        ]);

        $blobA = $service->pngBlob($service->render($this->order));
        $blobB = $service->pngBlob($service->render($otherOrder));

        $this->assertNotSame($blobA, $blobB);
    }

    public function test_format_png_returns_png_on_admin_and_client_routes(): void
    {
        $adminResponse = $this->actingAs($this->admin)
            ->get(route('admin.sourcing-orders.shipping-label', $this->order).'?format=png');
        $adminResponse->assertStatus(200);
        $this->assertSame('image/png', $adminResponse->headers->get('Content-Type'));

        $clientResponse = $this->actingAs($this->client)
            ->get(route('client.sourcing-orders.shipping-label', ['locale' => 'eng', 'sourcingOrder' => $this->order]).'?format=png');
        $clientResponse->assertStatus(200);
        $this->assertSame('image/png', $clientResponse->headers->get('Content-Type'));
    }

    public function test_per_destination_label_png_is_valid(): void
    {
        $destination = $this->request->destinations()->first();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sourcing-orders.shipping-label.destination', [
                'sourcingOrder' => $this->order,
                'destination' => $destination->id,
            ]).'?format=png');

        $response->assertStatus(200);
        $this->assertSame('image/png', $response->headers->get('Content-Type'));

        $blob = $response->getContent();
        $this->assertSame("\x89PNG\r\n\x1a\n", substr($blob, 0, 8));
    }

    public function test_long_address_word_wraps_without_exception(): void
    {
        $this->request->destinations()->first()->update([
            'address' => 'Very long recipient address that needs to wrap over multiple lines because it contains so many words and characters making it exceed the single line width of the label value cell significantly',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sourcing-orders.shipping-label', $this->order).'?format=png');

        $response->assertStatus(200);
        $this->assertSame('image/png', $response->headers->get('Content-Type'));
    }

    public function test_missing_logo_does_not_crash_rendering(): void
    {
        config(['fsb.label.logo_path' => base_path('nonexistent-logo.png')]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sourcing-orders.shipping-label', $this->order).'?format=png');

        $response->assertStatus(200);
        $this->assertSame('image/png', $response->headers->get('Content-Type'));
    }

    public function test_no_format_downloads_png_by_default_and_disabled_flag_falls_back_to_pdf(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sourcing-orders.shipping-label', $this->order));
        $response->assertStatus(200);
        $this->assertSame('image/png', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('attachment; filename="shipping-label-'.$this->order->id.'.png"', $response->headers->get('Content-Disposition'));
        $this->assertSame("\x89PNG\r\n\x1a\n", substr($response->getContent(), 0, 8));

        FeatureFlag::query()->updateOrCreate(
            ['key' => 'label_image_output'],
            ['name' => 'Label Image Output', 'status' => 'hidden']
        );

        $flaggedResponse = $this->actingAs($this->admin)
            ->get(route('admin.sourcing-orders.shipping-label', $this->order).'?format=png');
        $flaggedResponse->assertStatus(200);
        $this->assertSame('application/pdf', $flaggedResponse->headers->get('Content-Type'));
    }
}
