<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourcingOrder>
 */
class SourcingOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'quotation_id' => \App\Models\Quotation::factory(),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'status' => \App\Models\SourcingOrder::STATUSES[array_rand(\App\Models\SourcingOrder::STATUSES)],
            'proof_of_payment_path' => null,
            'tracking_number' => null,
            'tracking_carrier' => null,
        ];
    }
}
