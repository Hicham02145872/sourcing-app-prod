<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotation>
 */
class QuotationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sourcing_request_id' => \App\Models\SourcingRequest::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'currency' => $this->faker->currencyCode(),
            'status' => $this->faker->randomElement(['pending', 'sent', 'accepted', 'rejected', 'expired']),
            'unit_price' => $this->faker->randomFloat(2, 10, 100),
            'commission_service' => $this->faker->randomFloat(2, 5, 50),
            'unit_weight' => $this->faker->randomFloat(2, 0.1, 10),
            'delivery_cost_china' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
