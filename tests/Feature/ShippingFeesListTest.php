<?php

namespace Tests\Feature;

use App\Livewire\Client\ShippingFeesList;
use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShippingFeesListTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_fees_list_renders_correctly()
    {
        Livewire::test(ShippingFeesList::class)
            ->assertStatus(200);
    }

    public function test_select_country_populates_selected_country()
    {
        $country = Country::factory()->create(['name' => 'Test Country']);
        $shippingFee = ShippingFee::factory()->create(['country_id' => $country->id]);
        ShippingFeeItem::factory()->create(['shipping_fee_id' => $shippingFee->id, 'transport_type' => 'air']);

        Livewire::test(ShippingFeesList::class)
            ->call('selectCountry', $country->id)
            ->assertSet('selectedCountry.id', $country->id)
            ->assertSee('Test Country'); // It should be in the modal if rendered
    }

    public function test_close_country_details_clears_selection()
    {
        $country = Country::factory()->create();

        Livewire::test(ShippingFeesList::class)
            ->call('selectCountry', $country->id)
            ->assertSet('selectedCountry.id', $country->id)
            ->call('closeCountryDetails')
            ->assertSet('selectedCountry', null);
    }

    public function test_air_tab_excludes_uae_labeled_columns(): void
    {
        $country = Country::factory()->create();
        $shippingFee = ShippingFee::factory()->create(['country_id' => $country->id]);
        ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $shippingFee->id,
            'transport_type' => 'air',
            'item_style' => 'FROM CHINA (normal goods)',
        ]);
        ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $shippingFee->id,
            'transport_type' => 'air',
            'item_style' => 'FROM DUBAI (normal goods)',
        ]);

        Livewire::test(ShippingFeesList::class)
            ->call('selectCountry', $country->id)
            ->assertSet('detailTab', 'air')
            ->assertSee('FROM CHINA (normal goods)')
            ->assertDontSee('FROM DUBAI (normal goods)');
    }

    public function test_uae_tab_shows_dubai_labeled_rows_even_when_saved_as_air(): void
    {
        $country = Country::factory()->create();
        $shippingFee = ShippingFee::factory()->create(['country_id' => $country->id]);
        ShippingFeeItem::factory()->create([
            'shipping_fee_id' => $shippingFee->id,
            'transport_type' => 'air',
            'item_style' => 'FROM DUBAI (normal goods)',
        ]);

        Livewire::test(ShippingFeesList::class)
            ->call('selectCountry', $country->id)
            ->assertSet('detailTab', 'train')
            ->assertSee('FROM DUBAI (normal goods)');
    }
}
