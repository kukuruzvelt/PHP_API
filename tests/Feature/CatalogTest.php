<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_structure(): void
    {
        $response = $this->get('/api/catalog/?page=1');

        $response->assertJson(fn(AssertableJson $json) => $json->has('data', env('PAGE_SIZE')
            , fn(AssertableJson $json) => $json->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'price' => 'integer',
                    'description' => 'string',
                    'quantity' => 'integer',
                ]
            )->etc())->has('meta')->has('links')
            ->whereAllType([
                    'meta' => 'array',
                    'data' => 'array',
                    'links' => 'array'
                ]
            )
        )->assertStatus(200);
    }

    public function test_page_param(): void
    {
        $page_num = 2;

        $response = $this->get('/api/catalog/?page=' . $page_num);

        $this->assertEquals($response->json('meta.current_page'), $page_num);

        $response->assertStatus(200);
    }

    public function test_category_param(): void
    {
        $response = $this->get('/api/catalog/?page=1&category=3');

        $category_id = 3;

        $response->assertJson(fn(AssertableJson $json) => $json->has('data',
            fn(AssertableJson $json) => $json->each(fn(AssertableJson $json) => $json->where('category_id', $category_id)->etc()))
            ->has('meta')->has('links'))
            ->assertStatus(200);
    }

    public function test_single_word_text_param(): void
    {
        $search_for = '1';

        $response = $this->get('/api/catalog/?page=1&text=' . $search_for);

        $response->assertJson(fn(AssertableJson $json) => $json->has('data',
            fn(AssertableJson $json) => $json->each(fn(AssertableJson $json) => $json->where('name',
                fn(string $name) => str($name)->contains($search_for))->etc()))
            ->has('meta')->has('links'))
            ->assertStatus(200);
    }

    public function test_multiple_words_text_param(): void
    {
        $search_for_first = 'product';
        $search_for_second = '1';

        $response = $this->get('/api/catalog/?page=1&text=' . $search_for_first . '_' . $search_for_second);

        $response->assertJson(fn(AssertableJson $json) => $json->has('data',
            fn(AssertableJson $json) => $json->each(fn(AssertableJson $json) => $json->where('name',
                fn(string $name) => str(strtolower($name))->contains($search_for_first . ' ' . $search_for_second))->etc()))
            ->has('meta')->has('links'))
            ->assertStatus(200);
    }

    public function test_cheap_sort(): void
    {
        $response = $this->get('/api/catalog/?page=1&sort=cheap');

        $response->assertStatus(200);

        $this->assertGreaterThan($response->json('data.0.price'),
            $response->json('data.' . env('PAGE_SIZE') - 1 . '.price'));
    }

    public function test_novelty_sort(): void
    {
        $response = $this->get('/api/catalog/?page=1&sort=novelty');

        $response->assertStatus(200);

        $this->assertLessThan($response->json('data.0.id'),
            $response->json('data.' . env('PAGE_SIZE') - 1 . '.id'));
    }

    public function test_expensive_sort(): void
    {
        $response = $this->get('/api/catalog/?page=1&sort=expensive');

        $response->assertStatus(200);

        $this->assertLessThan($response->json('data.0.price'),
            $response->json('data.' . env('PAGE_SIZE') - 1 . '.price'));
    }

}
