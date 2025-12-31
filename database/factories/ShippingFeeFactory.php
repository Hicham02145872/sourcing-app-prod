<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\ShippingFee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingFeeFactory extends Factory
{
    protected $model = ShippingFee::class;

    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'sea_fee' => $this->faker->randomFloat(2, 10, 100),
            'train_fee' => $this->faker->randomFloat(2, 10, 100),
            'air_normal_fee' => $this->faker->randomFloat(2, 5, 50),
            'air_brand_fee' => $this->faker->randomFloat(2, 5, 50),
            'air_battery_fee' => $this->faker->randomFloat(2, 5, 50),
            'air_liquid_fee' => $this->faker->randomFloat(2, 5, 50),
            'currency' => 'USD',
            'unit' => 'kg',
        ];
    }
}
