<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\Authentication;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use Authentication;

    public function test_getting_user(): void
    {
        $user = $this->getLoggedUser();

        $response = $this->get('/api/user');

        $response->assertStatus(200);
        $response->assertJson(['id' => $user->id,]);
    }

    public function test_payment()
    {
        $user = $this->getLoggedUser();

        $money_amount = 111;

        $response = $this->post('/api/user/pay', ['money_amount' => $money_amount]);

        $response->assertStatus(200);
        $this->assertEquals($money_amount + $user->money, User::whereId($user->id)->first()->money);
    }

    public function test_payment_with_no_params()
    {
        $user = $this->getLoggedUser();

        $response = $this->post('/api/user/pay');

        $response->assertStatus(400);
        $this->assertEquals(trans('messages.no_params_passed'), $response->json('error_message'));
    }
}
