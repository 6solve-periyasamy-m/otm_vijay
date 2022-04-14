<?php

namespace App\Repository;

use App\Models\System\ApiToken;
use App\Models\User;
use Bouncer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class UserRepository
{

    public static function getLatestToken(User $user): ApiToken
    {
        $token = $user->tokens()->latest()->first();
        if (!(isset($token) && !$token->hasExpired())) $token = $user->generateToken();
        return $token;
    }

    public static function getUserFromToken(string $token): User
    {
        $apiToken = ApiToken::findOrFail($token);
        return $apiToken->user;
    }

    public static function generateUserToken(User $user, int $expiresIn = ApiToken::DEFAULT_EXPIRY): ApiToken
    {
        // This will attempt to create an API key, and re-attempt if a collision occurs. Should be rare, but may bite us in future
        while (true) {
            try {
                $apiToken = ApiToken::make([
                    'token' => Str::random(32),
                    'expiry' => now()->addMinutes($expiresIn),
                ]);
                $user->tokens()->save($apiToken);
                return $apiToken;
            } catch (QueryException $ignored) {
                continue;
            }
        }
    }

    public static function purgeUserTokens(User $user, int $limit = ApiToken::DEFAULT_LIMIT): void
    {
        foreach ($user->tokens as $token) {
            if (now()->addHours($limit * -1)->isAfter($token->expiry)) {
                $token->forceDelete();
            }
        }
    }

    public static function invalidateAllUserTokens(User $user): void
    {
        foreach ($user->tokens as $token) {
            if (!$token->hasExpired()) {
                $token->invalidate();
            }
        }
    }

    public static function getRemainingUserCount(): int
    {
        $amount = config('app.user-limit');
        foreach (User::all() as $user) {
            if (Bouncer::is($user)->a('otm-staff')) continue;
            $amount--;
        }
        return $amount;
    }
}
