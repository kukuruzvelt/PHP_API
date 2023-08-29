<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test User First Name',
            'last_name' => 'Test User Last Name',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    public function test_new_users_can_not_register_with_blank_name(): void
    {
        $response = $this->post('/register', [
            'last_name' => 'Test User Last Name',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_new_users_can_not_register_with_blank_surname(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test User First Name',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_new_users_can_not_register_with_blank_email(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test User First Name',
            'last_name' => 'Test User Last Name',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_new_users_can_not_register_with_blank_password(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test User First Name',
            'last_name' => 'Test User Last Name',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
    }

    public function test_new_users_can_not_register_with_short_password(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test User First Name',
            'last_name' => 'Test User Last Name',
            'email' => 'test@example.com',
            'password' => 'pass',
        ]);

        $response->assertStatus(302);
    }

    public function test_new_users_can_not_register_with_duplicate_email(): void
    {
        for ($i = 0; $i < 2; $i++) {
            $response = $this->post('/register', [
                'first_name' => 'Test User First Name',
                'last_name' => 'Test User Last Name',
                'email' => 'test@example.com',
                'password' => 'password',
            ]);
        }

        $response->assertStatus(302);
    }
}
