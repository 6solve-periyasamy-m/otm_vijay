<?php

namespace App\Models\Helper;

enum ModelEventType: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case RESTORED = 'restored';
    case LOGIN_SUCCESS = 'login';
    case LOGIN_FAILED_PASSWORD = 'login-failed-password';
    case LOGIN_FAILED_OTP_MISSING = 'login-failed-otp-missing';
    case LOGIN_FAILED_OTP_FAILED = 'login-failed-otp-failed';
    case LOGOUT = 'logout';
    case REGISTER = 'register';
    case PASSWORD_RESET = 'password-reset';
    case PASSWORD_CHANGED = 'password-changed';
    case OTP_ENABLED = 'otp-enabled';
    case OTP_DISABLED = 'otp-disabled';

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::UPDATED => 'Updated',
            self::DELETED => 'Deleted',
            self::RESTORED => 'Restored',
            self::LOGIN_SUCCESS => 'Login Successful',
            self::LOGIN_FAILED_PASSWORD => 'Login Failed: Incorrect Password',
            self::LOGIN_FAILED_OTP_MISSING => 'Login Failed: OTP Missing',
            self::LOGIN_FAILED_OTP_FAILED => 'Login Failed: Incorrect OTP',
            self::LOGOUT => 'Logout',
            self::REGISTER => 'Register',
            self::PASSWORD_RESET => 'Password Reset',
            self::PASSWORD_CHANGED => 'Password Changed',
            self::OTP_ENABLED => 'OTP Enabled',
            self::OTP_DISABLED => 'OTP Disabled',
            default => 'Unknown'
        };
    }
}
