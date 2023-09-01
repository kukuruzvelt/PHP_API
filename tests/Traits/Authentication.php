<?php

namespace Tests\Traits;

use Illuminate\Tests\Foundation\Testing\TestCaseWithTrait;
use Tests\TestCase;

trait Authentication
{
    public function login($user)
    {
        return $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    }
}
