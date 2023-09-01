<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;


    public function test_getting_category(): void
    {
        $id = 1;

        $response = $this->get('/api/category/?id=' . $id);

        $this->assertEquals($id, $response->json('data.id'));

        $response->assertStatus(200);
    }

    public function test_getting_category_with_wrong_id(): void
    {
        $id = env('CATEGORY_NUM_TO_SEED') + 1;

        $response = $this->get('/api/category/?id=' . $id);

        $this->assertEquals(trans('messages.no_category_with_such_id'), $response->json('error_message'));

        $response->assertStatus(404);
    }

    public function test_getting_category_no_params_passed(): void
    {
        $response = $this->get('/api/category');

        $this->assertEquals(trans('messages.no_params_passed'), $response->json('error_message'));

        $response->assertStatus(400);
    }

    public function test_getting_all_categories(): void
    {
        $response = $this->get('/api/category/all');

        $response->assertJson(fn(AssertableJson $json) => $json->has('data', env('CATEGORY_NUM_TO_SEED')));

        $response->assertStatus(200);
    }
}
