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
            'name' => "Product " . fake()->unique()->numberBetween(1, env('PRODUCT_NUM_TO_SEED')*10),
            'price' => fake()->numberBetween(10, 100),
            'description' => fake()->text(100),
            'quantity' => fake()->numberBetween(0, 100),
            'category_id' => fake()->numberBetween(1, env('CATEGORY_NUM_TO_SEED')),
        ];
    }
}
