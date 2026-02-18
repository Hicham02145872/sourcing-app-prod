<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Database\Seeder;

/**
 * Seeder basé sur la structure Air Freight DDP Table (images fournies).
 * Remplit shipping_fees et shipping_fee_items pour : Morocco, UAE, Europe (Allemagne, France, etc.), Afrique (Uganda, Cameroon, etc.).
 */
class AirFreightDDPTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMoroccoAir();
        $this->seedUaeDubaiAir();
        $this->seedEuropeAir();
        $this->seedAfricaAir();
        $this->seedSeaFreightSample();
    }

    /** Shipping to Morocco (Casablanca) - Air */
    protected function seedMoroccoAir(): void
    {
        $country = Country::where('code', 'MA')->first();
        if (! $country) {
            return;
        }
        $fee = ShippingFee::firstOrCreate(
            ['country_id' => $country->id],
            ['currency' => 'USD', 'unit' => 'kg', 'air_arrival_time' => '15-20']
        );

        $items = [
            ['General goods', 12.00, '15-20'],
            ['General Cargo Have battery', 15.50, '15-20'],
        ];
        foreach ($items as [$style, $price, $days]) {
            ShippingFeeItem::updateOrCreate(
                [
                    'shipping_fee_id' => $fee->id,
                    'transport_type' => 'air',
                    'item_style' => $style,
                ],
                ['price_per_kg' => $price, 'estimation_days' => $days, 'estimation_unit' => 'days']
            );
        }
    }

    /** China to Dubai Air - Dubai / Sharjah / FBA */
    protected function seedUaeDubaiAir(): void
    {
        $country = Country::where('code', 'AE')->first();
        if (! $country) {
            return;
        }
        $fee = ShippingFee::firstOrCreate(
            ['country_id' => $country->id],
            ['currency' => 'USD', 'unit' => 'kg', 'air_arrival_time' => '5-10']
        );

        $items = [
            ['General cargo', 6, 'about 5-8 days'],
            ['Electricity and magnetism products', 6, 'about 5-8 days'],
            ['Mobile power bank, pure battery, cosmetics, food', 7, 'about 8-10 days'],
            ['Health care products', 10, 'about 8-10 days'],
        ];
        foreach ($items as [$style, $price, $days]) {
            ShippingFeeItem::updateOrCreate(
                [
                    'shipping_fee_id' => $fee->id,
                    'transport_type' => 'air',
                    'item_style' => $style,
                ],
                ['price_per_kg' => $price, 'estimation_days' => $days, 'estimation_unit' => 'days']
            );
        }
    }

    /** China to Europe air - Allemagne, France, Pays-Bas, etc. (prix par pays depuis les images) */
    protected function seedEuropeAir(): void
    {
        $days = '10-15';
        $styles = [
            'general cargo without brand',
            'general cargo with brand',
            'Clothing, shoes, bags, cosmetics (up to 300 ml), electrically conductive and magnetic items, some liquids, some powders, health supplements, medications, mobile phones, watches.',
        ];
        $pricesByCountry = [
            'DE' => [11.0, 13.0, 12.5], 'NL' => [11.0, 13.0, 12.7], 'BE' => [11.0, 13.0, 12.7], 'LU' => [11.0, 13.0, 12.7],
            'FR' => [12.5, 14.0, 13.8], 'IT' => [12.5, 14.0, 13.8], 'ES' => [12.5, 14.0, 13.8], 'AT' => [12.5, 14.0, 13.8],
            'PL' => [12.5, 14.5, 14.2], 'CZ' => [12.5, 14.5, 14.2], 'DK' => [12.5, 14.5, 14.2], 'FI' => [12.5, 14.5, 14.2],
            'SE' => [12.5, 14.5, 14.2], 'HR' => [12.5, 14.5, 14.2], 'SK' => [12.5, 14.5, 14.2], 'SI' => [12.5, 14.5, 14.2],
            'HU' => [12.5, 14.5, 14.5], 'LT' => [12.5, 14.5, 14.5], 'PT' => [12.5, 14.5, 14.5], 'IE' => [12.5, 14.5, 14.5],
            'GR' => [12.5, 14.5, 14.5], 'BG' => [12.5, 14.5, 14.5], 'RO' => [12.5, 14.5, 14.5], 'LV' => [12.5, 14.5, 14.5], 'EE' => [12.5, 14.5, 14.5],
        ];

        foreach ($pricesByCountry as $code => $prices) {
            $country = Country::where('code', $code)->first();
            if (! $country) {
                continue;
            }
            $fee = ShippingFee::firstOrCreate(
                ['country_id' => $country->id],
                ['currency' => 'USD', 'unit' => 'kg', 'air_arrival_time' => $days]
            );
            foreach ($styles as $i => $style) {
                ShippingFeeItem::updateOrCreate(
                    [
                        'shipping_fee_id' => $fee->id,
                        'transport_type' => 'air',
                        'item_style' => $style,
                    ],
                    ['price_per_kg' => $prices[$i] ?? 12.5, 'estimation_days' => $days, 'estimation_unit' => 'days']
                );
            }
        }
    }

    /** China to Africa Air - Uganda, Cameroon, Burkina Faso, Côte d'Ivoire, Kenya, Senegal */
    protected function seedAfricaAir(): void
    {
        $data = [
            'UG' => [['General goods', 12.5, '9-12 days'], ['Have battery', 16.5, '12-14 days']],
            'CM' => [['General goods', 12.8, '9-12 days'], ['Have battery', 13, '12-14 days']],
            'BF' => [['General goods', 18, '9-12 days'], ['Have battery', 20, '9-12 days']],
            'CI' => [['General goods', 18, '9-12 days']],
            'KE' => [['General goods', 17.24, '3 days'], ['Have battery', 21, '12-14 days']],
            'SN' => [['General goods', 16.49, '10-14 days']],
            'CG' => [['General goods', 16, '10-14 days']],
            'NE' => [['General goods', 16.49, '10-14 days']],
            'GN' => [['General goods', 17.24, '3-4 days']],
            'GA' => [['General goods', 18.5, '10-14 days']],
        ];

        foreach ($data as $code => $items) {
            $country = Country::where('code', $code)->first();
            if (! $country) {
                continue;
            }
            $fee = ShippingFee::firstOrCreate(
                ['country_id' => $country->id],
                ['currency' => 'USD', 'unit' => 'kg', 'air_arrival_time' => '9-14']
            );
            foreach ($items as [$style, $price, $days]) {
                ShippingFeeItem::updateOrCreate(
                    [
                        'shipping_fee_id' => $fee->id,
                        'transport_type' => 'air',
                        'item_style' => $style,
                    ],
                    ['price_per_kg' => $price, 'estimation_days' => $days, 'estimation_unit' => 'days']
                );
            }
        }
    }

    /** Sea freight (exemple) - General cargo, Monitor/cargo with brand, cosmetics/food */
    protected function seedSeaFreightSample(): void
    {
        $country = Country::where('code', 'MA')->first();
        if (! $country) {
            return;
        }
        $fee = ShippingFee::where('country_id', $country->id)->first();
        if (! $fee) {
            return;
        }
        $fee->update(['sea_arrival_time' => '30-35']);
        $seaItems = [
            ['General cargo', 10.00, '30-35 day'],
            ['Monitor, cargo with brand', 15.00, '30-35 day'],
            ['cosmetics, food', 18.00, '30-35 day'],
        ];
        foreach ($seaItems as [$style, $price, $days]) {
            ShippingFeeItem::updateOrCreate(
                [
                    'shipping_fee_id' => $fee->id,
                    'transport_type' => 'sea',
                    'item_style' => $style,
                ],
                ['price_per_kg' => $price, 'estimation_days' => $days, 'estimation_unit' => 'days']
            );
        }
    }
}
