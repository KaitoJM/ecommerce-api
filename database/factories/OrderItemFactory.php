<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_snapshot_name' => fake()->word(),
            'product_snapshot_price' => fake()->numberBetween(1000, 5000),
            'quantity' => fake()->numberBetween(1, 3),
            'total' => fake()->numberBetween(5000, 10000)
        ];
    }
}
