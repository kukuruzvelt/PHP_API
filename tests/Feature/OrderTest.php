<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use Database\Factories\CartFactory;
use Database\Factories\ProductFactory;
use Database\Seeders\CartSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Traits\Authentication;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    use Authentication;

    public function test_get_all_orders(): void
    {
        $user = $this->getLoggedUser();

        $response = $this->get('/api/order/all/?page=1');

        $num_of_orders_to_expect = env('ORDER_NUM_TO_SEED');
        if ($num_of_orders_to_expect > env('PAGE_SIZE')) {
            $num_of_orders_to_expect = env('PAGE_SIZE');
        }

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('data', $num_of_orders_to_expect)->has('meta')->has('links'));
    }

    public function test_getting_products()
    {
        $user = $this->getLoggedUser();

        $order_id = 1;

        $response = $this->get('/api/order/getProducts/?order_id=' . $order_id);

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('data', env('PRODUCTS_IN_ORDER_NUM_TO_SEED')));
    }

    public function test_getting_products_with_no_params()
    {
        $user = $this->getLoggedUser();

        $response = $this->get('/api/order/getProducts');

        $response->assertStatus(400);
        $this->assertEquals(trans('messages.no_params_passed'), $response->json('error_message'));
    }

    public function test_getting_products_for_non_existing_order()
    {
        $user = $this->getLoggedUser();

        $order_id = env('ORDER_NUM_TO_SEED') + 1;

        $response = $this->get('/api/order/getProducts/?order_id=' . $order_id);

        $response->assertStatus(404);
        $this->assertEquals(trans('messages.no_order_with_such_id'), $response->json('error_message'));
    }


    /**
     * Be aware, that seeded user may not have enough money to order all products in cart,
     * @see ProductFactory, \UserFactory, CartFactory for details
     */
    public function test_create_order()
    {
        $user = $this->getLoggedUser();

        $num_of_products_in_cart = Cart::whereUserId($user->id)->count();

        $response = $this->post('/api/order/create', ['city' => fake()->city, 'date' => fake()->date]);

        $new_order_id = env('ORDER_NUM_TO_SEED') + 1;

        $response->assertStatus(200);
        $this->assertTrue(Order::whereId($new_order_id)->exists());
        $this->assertEquals($num_of_products_in_cart, OrderProduct::whereOrderId($new_order_id)->count());
    }

    public function test_create_order_with_missing_params()
    {
        $user = $this->getLoggedUser();

        $response = $this->post('/api/order/create');

        $response->assertStatus(400);
        $this->assertEquals(trans('messages.some_params_missing'), $response->json('error_message'));
    }

    public function test_create_order_with_no_money()
    {
        $user = $this->getLoggedUser();
        $user->money = 0;
        $user->save();

        $response = $this->post('/api/order/create', ['city' => fake()->city, 'date' => fake()->date]);

        $response->assertStatus(402);
        $this->assertEquals(trans('messages.not_enough_money'), $response->json('error_message'));
    }

    public function test_cansel_order()
    {
        $user = $this->getLoggedUser();

        $order_id = 1;

        $response = $this->post('/api/order/cancel', ['order_id' => $order_id]);

        $response->assertStatus(200);
        $this->assertEquals('CANCELED', Order::whereId($order_id)->first()->status);
    }

    public function test_cansel_non_existing_order()
    {
        $user = $this->getLoggedUser();

        $order_id = env('ORDER_NUM_TO_SEED') + 1;

        $response = $this->post('/api/order/cancel', ['order_id' => $order_id]);

        $response->assertStatus(404);
        $this->assertEquals(trans('messages.no_order_with_such_id'), $response->json('error_message'));
    }

    public function test_cansel_order_with_no_params_passed()
    {
        $user = $this->getLoggedUser();

        $response = $this->post('/api/order/cancel');

        $response->assertStatus(400);
        $this->assertEquals(trans('messages.no_params_passed'), $response->json('error_message'));
    }
}
