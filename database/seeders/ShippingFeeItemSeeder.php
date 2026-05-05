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
            'Electr & Magnet (No Brand)',
            'Electr & Magnet (With Brand)',
            'General Cargo (No Brand)',
            'General Cargo (With Brand)',
            'Power Bank, Battery, Cosmetic',
            'Screens, Electr & Mag (No Brand)',
            'Screens, Electr & Mag (With Brand)',
            'Health Care Products',
        ];

        foreach ($fees as $fee) {
            $fee->update([
                'air_unit' => $fee->air_unit ?? 'kg',
                'sea_unit' => $fee->sea_unit ?? 'CBM',
                'train_unit' => $fee->train_unit ?? 'kg',
            ]);

            foreach (['air', 'sea', 'train'] as $type) {
                foreach ($styles as $style) {
                    ShippingFeeItem::create([
                        'shipping_fee_id' => $fee->id,
                        'transport_type' => $type,
                        'item_style' => $style,
                        'price_per_kg' => rand(5, 15) + (rand(0, 99) / 100),
                    ]);
                }
            }
        }
    }
}
