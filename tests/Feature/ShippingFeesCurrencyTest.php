<?php

namespace Tests\Feature;

use App\Livewire\Admin\ShippingFeesTable;
use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShippingFeesCurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_fees_table_shows_currency_column()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $country = Country::factory()->create();
        ShippingFee::factory()->create([
            'country_id' => $country->id,
            'currency' => 'EUR',
        ]);

        Livewire::actingAs($admin)
            ->test(ShippingFeesTable::class)
            ->assertSee('EUR');
    }

    public function test_shipping_fees_table_shows_default_for_country_without_fees()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $country = Country::factory()->create();

        Livewire::actingAs($admin)
            ->test(ShippingFeesTable::class)
            ->assertSee($country->name);
    }

    public function test_shipping_fees_table_shows_currency_for_multiple_countries()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $countryUsd = Country::factory()->create(['name' => 'USA']);
        ShippingFee::factory()->create([
            'country_id' => $countryUsd->id,
            'currency' => 'USD',
        ]);

        $countryEur = Country::factory()->create(['name' => 'France']);
        ShippingFee::factory()->create([
            'country_id' => $countryEur->id,
            'currency' => 'EUR',
        ]);

        Livewire::actingAs($admin)
            ->test(ShippingFeesTable::class)
            ->assertSee('USD')
            ->assertSee('EUR');
    }
}
