<?php

namespace Database\Seeders;

use App\Models\OrderProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $numberOfOrders = env('ORDER_NUM_TO_SEED');
        $productsPerOrder = env('PRODUCTS_IN_ORDER_NUM_TO_SEED');

        for ($orderNumber = 1; $orderNumber <= $numberOfOrders; $orderNumber++) {
            for ($productNumber = 1; $productNumber <= $productsPerOrder; $productNumber++) {
                OrderProduct::create([
                    'order_id' => $orderNumber,
                    'product_id' => fake()->numberBetween(1, env('PRODUCT_NUM_TO_SEED')),
                    'quantity' => fake()->numberBetween(1, 100),
                ]);
            }
        }
    }
}
