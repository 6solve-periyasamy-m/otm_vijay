<?php

namespace App\Repository;

use App\Models\ApiToken;
use App\Models\User;

interface UserRepositoryInterface
{
    public static function getLatestToken(User $user) : ApiToken;
}

class UserRepository implements UserRepositoryInterface
{

    public static function getLatestToken(User $user) : ApiToken
    {
        $token = $user->tokens()->latest()->first();
        if (!(isset($token) && !$token->hasExpired())) $token = $user->generateToken();
        return $token;
    }
}
