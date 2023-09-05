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
        /**
         * user_id always equals 1 because only one user is seeded @see UserSeeder
         */
        return [
            'user_id' => 1,
            'date' => fake()->date(),
            'city' => fake()->city(),
            'status' => trans('statuses.in_progress'),
        ];
    }
}
