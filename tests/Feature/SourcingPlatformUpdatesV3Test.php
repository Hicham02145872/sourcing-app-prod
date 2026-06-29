<?php

namespace Tests\Feature;

use App\Livewire\Admin\DeliveryContentManager;
use App\Models\Country;
use App\Models\DeliveryDefect;
use App\Models\DeliveryNotice;
use App\Models\DeliveryProperty;
use App\Models\Quotation;
use App\Models\QuotationMedia;
use App\Models\Service;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\SourcingRequestDestination;
use App\Models\User;
use App\Services\ImageProcessingService;
use App\Services\ImageResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class SourcingPlatformUpdatesV3Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Session::start();
    }

    // ---- 8.1 Split indirect cost fields ----

    public function test_admin_can_save_and_retrieve_split_indirect_cost_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $country = Country::factory()->create();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\ShippingFeeEdit::class, ['country' => $country])
            ->set('itemsData.air.0.item_style', 'Split Cost Test Item')
            ->set('itemsData.air.0.price_per_kg', 10.50)
            ->set('itemsData.air.0.price_per_kg_china_to_dubai', 5.50)
            ->set('itemsData.air.0.price_per_kg_dubai_to_africa', 8.75)
            ->call('save');

        $this->assertDatabaseHas('shipping_fee_items', [
            'transport_type' => 'air',
            'item_style' => 'Split Cost Test Item',
            'price_per_kg' => 10.50,
            'price_per_kg_china_to_dubai' => 5.50,
            'price_per_kg_dubai_to_africa' => 8.75,
        ]);
    }

    public function test_split_indirect_cost_fields_can_be_null()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $country = Country::factory()->create();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\ShippingFeeEdit::class, ['country' => $country])
            ->set('itemsData.air.0.item_style', 'Split Cost Null Test')
            ->set('itemsData.air.0.price_per_kg', 10.50)
            ->set('itemsData.air.0.price_per_kg_china_to_dubai', '')
            ->set('itemsData.air.0.price_per_kg_dubai_to_africa', '')
            ->call('save');

        $this->assertDatabaseHas('shipping_fee_items', [
            'transport_type' => 'air',
            'item_style' => 'Split Cost Null Test',
            'price_per_kg' => 10.50,
            'price_per_kg_china_to_dubai' => null,
            'price_per_kg_dubai_to_africa' => null,
        ]);
    }

    // ---- 8.2 Partner page split costs ----

    public function test_popup_rates_api_returns_split_cost_fields()
    {
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $fee = ShippingFee::factory()->create([
            'country_id' => $country->id,
            'currency' => 'USD',
        ]);

        ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $fee->id,
            'transport_type' => 'train',
            'item_style' => 'General Cargo (No Brand)',
            'price_per_kg' => 15.00,
            'price_per_kg_china_to_dubai' => 5.50,
            'price_per_kg_dubai_to_africa' => 8.75,
        ]);

        $response = $this->actingAs($client)
            ->get(route('client.shipping-fees.popup-rates', [
                'country' => $country->id,
                'transport' => 'air',
            ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'indirect' => [
                'items' => [
                    '*' => ['price_per_kg_china_to_dubai', 'price_per_kg_dubai_to_africa'],
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'price_per_kg_china_to_dubai' => '5.50',
            'price_per_kg_dubai_to_africa' => '8.75',
        ]);
    }

    // ---- 8.3 Delivery properties CRUD ----

    public function test_super_admin_can_create_delivery_property()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        Livewire::actingAs($superAdmin)
            ->test(DeliveryContentManager::class)
            ->set('activeTab', 'properties')
            ->set('property_title', 'Test Property')
            ->set('property_description', 'Test Description')
            ->set('property_icon', '🚚')
            ->set('property_delivery_type', 'direct')
            ->call('saveProperty');

        $this->assertDatabaseHas('delivery_properties', [
            'title' => 'Test Property',
            'description' => 'Test Description',
            'delivery_type' => 'direct',
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_toggle_delivery_property()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $property = DeliveryProperty::create([
            'delivery_type' => 'indirect',
            'title' => 'Toggle Test',
            'is_active' => true,
        ]);

        Livewire::actingAs($superAdmin)
            ->test(DeliveryContentManager::class)
            ->call('toggleProperty', $property->id);

        $this->assertDatabaseHas('delivery_properties', [
            'id' => $property->id,
            'is_active' => false,
        ]);
    }

    public function test_super_admin_can_delete_delivery_property()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $property = DeliveryProperty::create([
            'delivery_type' => 'direct',
            'title' => 'Delete Test',
            'is_active' => true,
        ]);

        Livewire::actingAs($superAdmin)
            ->test(DeliveryContentManager::class)
            ->call('deleteProperty', $property->id);

        $this->assertDatabaseMissing('delivery_properties', ['id' => $property->id]);
    }

    public function test_super_admin_can_create_delivery_defect()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        Livewire::actingAs($superAdmin)
            ->test(DeliveryContentManager::class)
            ->set('activeTab', 'defects')
            ->set('defect_title', 'Test Defect')
            ->set('defect_description', 'Defect Description')
            ->set('defect_delivery_type', 'indirect')
            ->call('saveDefect');

        $this->assertDatabaseHas('delivery_defects', [
            'title' => 'Test Defect',
            'delivery_type' => 'indirect',
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_save_delivery_notice()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        Livewire::actingAs($superAdmin)
            ->test(DeliveryContentManager::class)
            ->set('activeTab', 'notices')
            ->set('notice_body_text', 'Test notice body')
            ->set('notice_delivery_type', 'direct')
            ->call('saveNotice');

        $this->assertDatabaseHas('delivery_notices', [
            'body_text' => 'Test notice body',
            'delivery_type' => 'direct',
        ]);
    }

    // ---- 8.4 Delivery address in quotation views ----

    public function test_admin_quotation_show_contains_delivery_address()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $service = Service::factory()->create();
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
        ]);
        $dest = SourcingRequestDestination::create([
            'sourcing_request_id' => $sourcingRequest->id,
            'country_id' => $country->id,
            'service_id' => $service->id,
            'address' => '123 Test Street, City',
            'quantity' => 10,
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.quotations.show', $quotation));

        $response->assertOk();
        $response->assertSee('Delivery Address');
        $response->assertSee('123 Test Street, City');
    }

    public function test_admin_quotation_edit_contains_delivery_address()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $service = Service::factory()->create();
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
        ]);
        $dest = SourcingRequestDestination::create([
            'sourcing_request_id' => $sourcingRequest->id,
            'country_id' => $country->id,
            'service_id' => $service->id,
            'address' => '456 Edit Street, City',
            'quantity' => 10,
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.quotations.edit', $quotation));

        $response->assertOk();
        $response->assertSee('Delivery Addresses');
        $response->assertSee('456 Edit Street, City');
    }

    // ---- 8.5 Photo viewer modal ----

    public function test_quotation_edit_view_contains_photo_viewer()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'real_product_image' => 'quotations/real_images/test.jpg',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.quotations.edit', $quotation));

        $response->assertOk();
        $response->assertSee('Featured Product Photo');
        $response->assertSee('Delete');
    }

    // ---- 8.6 Per-photo delete ----

    public function test_admin_can_delete_individual_media_item()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
        ]);

        $file = UploadedFile::fake()->image('test-photo.jpg');
        $path = $file->store('quotations/media', 'public');

        $media = QuotationMedia::create([
            'quotation_id' => $quotation->id,
            'file_path' => $path,
            'file_type' => 'image',
            'sort_order' => 0,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($admin)
            ->delete(route('admin.quotation-media.destroy', $media), [
                '_token' => Session::token(),
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('quotation_media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_can_delete_featured_photo()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
        ]);

        $file = UploadedFile::fake()->image('featured.jpg');
        $path = $file->store('quotations/real_images', 'public');

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'real_product_image' => $path,
        ]);

        $this->assertNotNull($quotation->fresh()->real_product_image);

        $response = $this->actingAs($admin)
            ->delete(route('admin.quotations.featured-photo.destroy', $quotation), [
                '_token' => Session::token(),
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertNull($quotation->fresh()->real_product_image);
        Storage::disk('public')->assertMissing($path);
    }

    // ---- 8.7 Unlimited attachments ----

    public function test_admin_can_upload_more_than_10_files_to_sourcing_order()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'quoted',
        ]);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'accepted',
        ]);
        $sourcingOrder = SourcingOrder::factory()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'status' => 'paid',
        ]);

        $files = [];
        for ($i = 0; $i < 15; $i++) {
            $files[] = UploadedFile::fake()->image("photo-{$i}.jpg");
        }

        $response = $this->actingAs($admin)
            ->post(route('admin.sourcing-orders.media.store', $sourcingOrder), [
                'files' => $files,
                '_token' => Session::token(),
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertEquals(15, $sourcingOrder->media()->count());
    }
}
