<?php

namespace Tests\Feature;

use App\Livewire\Admin\ShippingFeeEdit;
use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShippingFeesDubaiPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_and_retrieve_price_per_kg_dubai()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $country = Country::factory()->create();

        Livewire::actingAs($admin)
            ->test(ShippingFeeEdit::class, ['country' => $country])
            ->set('itemsData.air.0.item_style', 'Electr & Magnet (No Brand)')
            ->set('itemsData.air.0.price_per_kg', 10.50)
            ->set('itemsData.air.0.price_per_kg_dubai', 12.75)
            ->call('save');

        $this->assertDatabaseHas('shipping_fee_items', [
            'transport_type' => 'air',
            'item_style' => 'Electr & Magnet (No Brand)',
            'price_per_kg' => 10.50,
            'price_per_kg_dubai' => 12.75,
        ]);
    }

    public function test_price_per_kg_dubai_can_be_null()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $country = Country::factory()->create();

        Livewire::actingAs($admin)
            ->test(ShippingFeeEdit::class, ['country' => $country])
            ->set('itemsData.air.0.item_style', 'Electr & Magnet (No Brand)')
            ->set('itemsData.air.0.price_per_kg', 10.50)
            ->set('itemsData.air.0.price_per_kg_dubai', '')
            ->call('save');

        $this->assertDatabaseHas('shipping_fee_items', [
            'transport_type' => 'air',
            'price_per_kg' => 10.50,
            'price_per_kg_dubai' => null,
        ]);
    }

    public function test_client_displays_price_per_kg_dubai_for_uae_items()
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
            'price_per_kg_dubai' => 18.50,
        ]);

        $this->actingAs($client)
            ->get(route('client.shipping-fees.index'))
            ->assertOk();
    }

    public function test_client_falls_back_to_price_per_kg_when_dubai_price_is_null()
    {
        $client = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $fee = ShippingFee::factory()->create([
            'country_id' => $country->id,
            'currency' => 'USD',
        ]);

        ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $fee->id,
            'transport_type' => 'air',
            'item_style' => 'General Cargo (No Brand)',
            'price_per_kg' => 15.00,
            'price_per_kg_dubai' => null,
        ]);

        $this->actingAs($client)
            ->get(route('client.shipping-fees.index'))
            ->assertOk();
    }
}
