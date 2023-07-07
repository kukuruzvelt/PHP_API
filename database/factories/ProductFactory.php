<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => "Product " . fake()->unique()->numberBetween(1, 10000),
            'price' => fake()->numberBetween(100, 10000),
            'description' => fake()->text(100),
            'quantity' => fake()->numberBetween(0, 100),
            'category_id' => fake()->numberBetween(1, 5),
        ];
    }
}
