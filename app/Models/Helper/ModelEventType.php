<?php

namespace App\Models\Helper;

enum ModelEventType: string
{
    case CREATED = 'Created';
    case UPDATED = 'Updated';
    case DELETED = 'Deleted';
    case RESTORED = 'Restored';
    case LOGIN_SUCCESS = 'Login Successful';
    case LOGIN_FAILED_PASSWORD = 'Login Failed: Incorrect Password';
    case LOGIN_FAILED_OTP_MISSING = 'Login Failed: OTP Missing';
    case LOGIN_FAILED_OTP_FAILED = 'Login Failed: Incorrect OTP';
    case LOGOUT = 'Logout';
    case REGISTER = 'Register';
    case PASSWORD_RESET = 'Password Reset Request';
    case PASSWORD_CHANGED = 'Password Changed';
    case OTP_ENABLED = 'OTP Enabled';
    case OTP_DISABLED = 'OTP Disabled';
}
