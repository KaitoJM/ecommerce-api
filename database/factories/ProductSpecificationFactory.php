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
            'price' => $this->faker->randomFloat(100, 100000),
            'stock' => $this->faker->numberBetween(0,100),
            'default' => $this->faker->boolean(),
            'sale' => $this->faker->boolean(),
            'sale_price' => $this->faker->randomFloat(100, 100000)
        ];
    }
}
