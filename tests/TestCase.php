<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Generate a JWT token for the given user.
     *
     * @return string
     */
    protected function getJwtToken(User $user)
    {
        return JWTAuth::fromUser($user);
    }
}
