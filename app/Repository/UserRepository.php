<?php

namespace App\Repository;

use App\Models\ApiToken;
use App\Models\User;

interface UserRepositoryInterface
{
    public static function getLatestToken(User $user) : ApiToken;
    public static function getUserFromToken(string $token) : User;
}

class UserRepository implements UserRepositoryInterface
{

    public static function getLatestToken(User $user) : ApiToken
    {
        $token = $user->tokens()->latest()->first();
        if (!(isset($token) && !$token->hasExpired())) $token = $user->generateToken();
        return $token;
    }

    public static function getUserFromToken(string $token) : User
    {
        $apiToken = ApiToken::findOrFail($token);
        return $apiToken->user;
    }
}
