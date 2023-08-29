<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_category(): void
    {
        $id = 3;

        $response = $this->get('/api/category/?id=' . $id);

        $this->assertEquals($id, $response->json('data.id'));

    }

    public function test_getting_all_categories(): void
    {

    }
}
