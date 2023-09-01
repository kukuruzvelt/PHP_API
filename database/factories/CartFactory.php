<?php

namespace Database\Factories;

use Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * user_id always equals 1 because only one user is seeded @see UserSeeder
         */
        return [
            'user_id' => 1,
            'product_id' => fake()->unique()->numberBetween(1, env('PRODUCT_NUM_TO_SEED')),
            'quantity' => fake()->numberBetween(0, 20),
        ];
    }
}
