<?php

namespace Database\Factories;

use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingFeeItemFactory extends Factory
{
    protected $model = ShippingFeeItem::class;

    public function definition()
    {
        return [
            'shipping_fee_id' => ShippingFee::factory(),
            'transport_type' => $this->faker->randomElement(['air', 'sea', 'train']),
            'item_style' => $this->faker->word,
            'price_per_kg' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
