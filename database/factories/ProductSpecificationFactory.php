<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSpecification>
 */
class ProductSpecificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => fake()->randomFloat(100, 100000),
            'stock' => fake()->numberBetween(0,100),
            'default' => fake()->boolean(),
            'sale' => fake()->boolean(),
            'sale_price' => fake()->randomFloat(100, 100000)
        ];
    }
}
