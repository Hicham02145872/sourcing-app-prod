<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RefundRequest>
 */
class RefundRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sourcing_order_id' => \App\Models\SourcingOrder::factory(),
            'user_id' => \App\Models\User::factory(),
            'assigned_to_admin_id' => \App\Models\User::factory()->state(['role' => 'admin']),
            'type' => $this->faker->randomElement(['full', 'partial']),
            'status' => 'pending',
            'amount_requested' => $this->faker->randomFloat(2, 10, 100),
            'reason_category' => $this->faker->word(),
            'reason_description' => $this->faker->paragraph(),
            'evidence_paths' => [],
        ];
    }
}
