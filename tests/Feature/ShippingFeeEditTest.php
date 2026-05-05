<?php

namespace Tests\Feature;

use App\Livewire\Admin\ShippingFeeEdit;
use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShippingFeeEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_fails_when_duplicate_categories_exist_in_same_transport_type(): void
    {
        $country = Country::factory()->create();

        Livewire::test(ShippingFeeEdit::class, ['country' => $country])
            ->set('itemsData.air', [
                [
                    'id' => null,
                    'item_style' => 'Duplicate Style',
                    'price_per_kg' => 9.50,
                    'estimation_days' => '5-7',
                    'estimation_unit' => 'days',
                    '_deleted' => false,
                ],
                [
                    'id' => null,
                    'item_style' => 'Duplicate Style',
                    'price_per_kg' => 10.50,
                    'estimation_days' => '6-8',
                    'estimation_unit' => 'days',
                    '_deleted' => false,
                ],
            ])
            ->set('itemsData.sea', [])
            ->set('itemsData.train', [])
            ->call('save')
            ->assertHasErrors(['duplicate_categories']);

        $this->assertDatabaseCount('shipping_fees', 0);
        $this->assertDatabaseCount('shipping_fee_items', 0);
    }

    public function test_save_removes_deleted_categories_for_transport_type(): void
    {
        $country = Country::factory()->create();
        $shippingFee = ShippingFee::factory()->create(['country_id' => $country->id]);

        $keptItem = ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $shippingFee->id,
            'transport_type' => 'air',
            'item_style' => 'Old Category A',
            'price_per_kg' => 9.5,
        ]);

        $deletedItem = ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $shippingFee->id,
            'transport_type' => 'air',
            'item_style' => 'Old Category B',
            'price_per_kg' => 12.0,
        ]);

        Livewire::test(ShippingFeeEdit::class, ['country' => $country])
            ->set('itemsData.air', [
                [
                    'id' => $keptItem->id,
                    'item_style' => 'Updated Category A',
                    'price_per_kg' => 10.75,
                    'estimation_days' => '5-7',
                    'estimation_unit' => 'days',
                ],
            ])
            ->set('itemsData.sea', [])
            ->set('itemsData.train', [])
            ->call('save');

        $this->assertDatabaseHas('shipping_fee_items', [
            'id' => $keptItem->id,
            'shipping_fee_id' => $shippingFee->id,
            'transport_type' => 'air',
            'item_style' => 'Updated Category A',
            'price_per_kg' => 10.75,
        ]);

        $this->assertDatabaseMissing('shipping_fee_items', [
            'id' => $deletedItem->id,
        ]);
    }
}
