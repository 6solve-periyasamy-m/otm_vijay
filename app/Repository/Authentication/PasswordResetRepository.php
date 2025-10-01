<?php

namespace App\Repository\Authentication;

use DB;
use Illuminate\Queue\TimeoutExceededException;
use Str;

class PasswordResetRepository
{
    protected const MAX_LOOPS = 100;
    /**
     * Get the email address related to a password reset token
     * @param string $token The password reset token
     * @return string|null The email associated if the token is valid, or null if invalid
     */
    public static function getResetEmail(string $token): string|null
    {
        return DB::table('password_resets')->where('token', '=', $token)->select()->first()?->email;
    }

    /**
     * Create a reset token for a specific email
     * @param string $email The email wishing to request
     * @return string The reset token
     * @throws TimeoutExceededException
     */
    public static function createResetRequest(string $email): string
    {
        $loops = 0;
        while (true) {
            if ($loops >= (self::MAX_LOOPS)) { throw new TimeoutExceededException('Cycled too many times.'); }
            $loops++;
            $token = Str::random(128);
            if (static::getResetEmail($token) !== null) continue;
            $success = DB::table('password_resets')->insert(['email' => $email, 'token' => $token, 'created_at' => now(),]);
            if ($success) {
                return $token;
            }
        }
    }

    public static function invalidateResetRequest(string $token): bool
    {
        return DB::table('password_resets')->where('token', '=', $token)->delete();
    }
}
