<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Traits\Authentication;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use Authentication;

    public function test_getting_cart(): void
    {
        $user = $this->getLoggedUser();

        $response = $this->get('/api/cart/?page=1');

        $num_of_products_in_cart = env('CART_NUM_TO_SEED');

        $response->assertJson(fn(AssertableJson $json) => $json->has('cart', $num_of_products_in_cart)
            ->has('total_price')->has('last_page'));
        $response->assertStatus(200);
    }

    public function test_adding_to_cart()
    {
        $user = $this->getLoggedUser();

        $product = Product::factory()->create();
        $quantity = 5;

        $response = $this->post('/api/cart/add', ['product_id' => $product->id, 'quantity' => $quantity]);

        $this->assertTrue(Cart::whereUserId($user->id)->whereProductId($product->id)->exists());
        $this->assertEquals($quantity, Cart::whereUserId($user->id)->whereProductId($product->id)->first()->quantity);
        $response->assertStatus(200);
    }

    public function test_adding_with_no_quantity_specified()
    {
        $user = $this->getLoggedUser();

        $product = Product::factory()->create();

        $response = $this->post('/api/cart/add', ['product_id' => $product->id]);

        $this->assertTrue(Cart::whereUserId($user->id)->whereProductId($product->id)->exists());
        $this->assertEquals(1, Cart::whereUserId($user->id)->whereProductId($product->id)->first()->quantity);
        $response->assertStatus(200);
    }

    public function test_exceeding_cart_limit()
    {
        $user = $this->getLoggedUser();

        while (Cart::whereUserId($user->id)->count() < env('MAX_CART_SIZE')) {
            Cart::factory()->create()->save();
        }

        $product = Product::factory()->create();

        $response = $this->post('/api/cart/add', ['product_id' => $product->id]);

        $response->assertStatus(429);
        $this->assertEquals(trans('messages.limit_of_products'), $response->json('error_message'));
    }

    public function test_product_out_of_stock()
    {
        $user = $this->getLoggedUser();

        $product = Product::factory()->create();

        $response = $this->post('/api/cart/add', ['product_id' => $product->id, 'quantity' => $product->quantity + 1]);

        $response->assertStatus(404);
        $this->assertEquals(trans('messages.product_out_of_stock'), $response->json('error_message'));
    }

    public function test_removing_from_cart()
    {
        $user = $this->getLoggedUser();

        $product = Product::whereId(Cart::whereUserId($user->id)->first()->product_id)->first();
        $product_quantity_in_cart_before_removing = Cart::whereUserId($user->id)->whereProductId($product->id)->first()->quantity;

        $response = $this->post('/api/cart/remove', ['product_id' => $product->id]);

        $this->assertEquals($product_quantity_in_cart_before_removing - 1, Cart::whereUserId($user->id)
            ->whereProductId($product->id)->first()->quantity);
        $this->assertEquals($product->quantity + 1, Product::whereId($product->id)->first()->quantity);
        $response->assertStatus(200);
    }


    public function test_removing_product_that_is_not_in_the_cart()
    {
        $user = $this->getLoggedUser();

        $product = Product::factory()->create();

        $response = $this->post('/api/cart/remove', ['product_id' => $product->id]);

        $response->assertStatus(409);
        $this->assertEquals(trans('messages.no_product_with_such_id_in_cart'), $response->json('error_message'));

    }

    public function test_removing_non_existing_product()
    {
        $user = $this->getLoggedUser();

        $product = Product::factory()->create();

        $response = $this->post('/api/cart/remove', ['product_id' => $product->id + 1]);

        $response->assertStatus(404);
        $this->assertEquals(trans('messages.no_product_with_such_id'), $response->json('error_message'));
    }

    public function test_adding_non_existing_product()
    {
        $user = $this->getLoggedUser();

        $product = Product::factory()->create();

        $response = $this->post('/api/cart/add', ['product_id' => $product->id + 1]);

        $response->assertStatus(404);
        $this->assertEquals(trans('messages.no_product_with_such_id'), $response->json('error_message'));
    }


}
