<?php

namespace Database\Seeders;

use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Database\Seeder;

class ShippingFeeItemSeeder extends Seeder
{
    public function run(): void
    {
        $fees = ShippingFee::all();

        $styles = [
            'electricity and magnetism products without brand',
            'electricity and magnetism products with brand',
            'general cargo without brand',
            'general cargo without brand ',
            'Mobile power bank, pure battery, cosmetics, food',
            'screens, electricity and magnetism without brand',
            'screens, electricity and magnetism with brand',
            'health care products',
        ];

        foreach ($fees as $fee) {
            foreach (['air', 'sea', 'train'] as $type) {
                foreach ($styles as $style) {
                    ShippingFeeItem::create([
                        'shipping_fee_id' => $fee->id,
                        'transport_type' => $type,
                        'item_style' => $style,
                        'price_16_49' => rand(5, 15) + (rand(0, 99) / 100),
                        'price_50_99' => rand(4, 14) + (rand(0, 99) / 100),
                        'price_100_499' => rand(3, 13) + (rand(0, 99) / 100),
                        'price_plus_500' => rand(2, 12) + (rand(0, 99) / 100),
                        'estimation_days' => '7-9',
                    ]);
                }
            }
        }
    }
}
