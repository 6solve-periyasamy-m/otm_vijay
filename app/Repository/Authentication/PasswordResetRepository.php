<?php

namespace App\Repository\Authentication;

use DB;
use Str;

class PasswordResetRepository
{
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
     */
    public static function createResetRequest(string $email): string
    {
        while (true) {
            $token = Str::random(128);
            $email = static::getResetEmail($token);
            if ($email === null) continue;
            $success = DB::table('password_resets')->insert(['email' => $email, 'token' => $token, 'created_at' => now(),]);
            if ($success) {
                return $token;
            }
        }
    }
}
