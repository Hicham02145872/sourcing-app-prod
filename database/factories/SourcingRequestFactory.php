<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourcingRequest>
 */
class SourcingRequestFactory extends Factory
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
            'product_name' => $this->faker->sentence(3),
            'product_url' => $this->faker->url(),
            'product_image' => null,
            'category_id' => \App\Models\Category::factory(),
            'note' => $this->faker->paragraph(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'shipping_method' => $this->faker->randomElement(['air', 'sea']),
            'status' => $this->faker->randomElement(['pending', 'in_review', 'quoted', 'accepted', 'rejected']),
        ];
    }
}
