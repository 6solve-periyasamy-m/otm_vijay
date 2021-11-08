<?php

namespace App\Repository;

use App\Models\User;

interface UserRepositoryInterface
{
    public static function getLatestToken(User $user);
}

class UserRepository implements UserRepositoryInterface
{

    public static function getLatestToken(User $user)
    {
        return $user->tokens()->orderBy('created', 'DESC')->first();
    }
}
