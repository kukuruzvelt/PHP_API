<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Tests\Foundation\Testing\TestCaseWithTrait;
use Tests\TestCase;
use function Symfony\Component\String\u;

trait Authentication
{
    public function getLoggedUser()
    {
        //Has already been seeded
        $user = User::whereId(1)->first();

        //Authenticating user
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        return $user;
    }
}
