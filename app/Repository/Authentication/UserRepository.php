<?php

namespace App\Repository\Authentication;

use App\Mail\PasswordResetMailable;
use App\Models\Helper\Enum\ModelEventType;
use App\Models\System\ApiToken;
use App\Models\User;
use Bouncer;
use EventLogger;
use Exception;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Log;
use Mail;

class UserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function requestReset(): bool
    {
        $token = PasswordResetRepository::createResetRequest($this->user->email);
        try {
            Mail::to($this->user->email)->send(new PasswordResetMailable($this->user->email, $token));
            return true;
        } catch (Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    public function resetPassword(string $token, string $password): bool
    {
        if (PasswordResetRepository::getResetEmail($token) === $this->user->email) {
            EventLogger::simple($this->user, ModelEventType::PASSWORD_RESET);
            $this->user->password = Hash::make($password);
            $this->user->save();
            return true;
        }
        return false;
    }

    public static function getUserFromToken(string $token): User
    {
        $apiToken = ApiToken::findOrFail($token);
        return $apiToken->user;
    }

    public function getLatestToken(): ApiToken
    {
        $token = $this->user->tokens()->latest()->first();
        if (!(isset($token) && !$token->hasExpired())) $token = $this->user->generateToken();
        return $token;
    }

    public function generateToken(int $expiry = ApiToken::DEFAULT_EXPIRY): ApiToken
    {
        while (true) {
            try {
                $apiToken = ApiToken::make([
                    'token' => Str::random(32),
                    'expiry' => now()->addMinutes($expiry),
                ]);
                $this->user->tokens()->save($apiToken);
                return $apiToken;
            } catch (QueryException $ignored) {
                continue;
            }
        }
    }

    public function purgeUserTokens(int $limit = ApiToken::DEFAULT_LIMIT): void
    {
        foreach ($this->user->tokens as $token) {
            if (now()->addHours($limit * -1)->isAfter($token->expiry)) {
                $token->forceDelete();
            }
        }
    }

    public function invalidateAllUserTokens(): void
    {
        foreach ($this->user->tokens as $token) {
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
