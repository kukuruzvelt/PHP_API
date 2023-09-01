<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_product(): void
    {
        $id = 1;

        $response = $this->get('/api/product/?id=' . $id);

        $this->assertEquals($id, $response->json('data.id'));

        $response->assertStatus(200);
    }


    public function test_getting_product_with_wrong_id(): void
    {
        $id = env('PRODUCT_NUM_TO_SEED') + 1;

        $response = $this->get('/api/product/?id=' . $id);

        $this->assertEquals(trans('messages.no_product_with_such_id'), $response->json('error_message'));

        $response->assertStatus(404);
    }

    public function test_getting_product_no_params_passed(): void
    {
        $response = $this->get('/api/product');

        $this->assertEquals(trans('messages.no_params_passed'), $response->json('error_message'));

        $response->assertStatus(400);
    }
}
