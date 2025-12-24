<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subtotal' => fake()->numberBetween(1000, 10000),
            'discount_total' => fake()->numberBetween(0, 1000),
            'tax_total' => fake()->numberBetween(0, 500),
            'total' => fake()->numberBetween(2000, 20000),
        ];
    }
}
