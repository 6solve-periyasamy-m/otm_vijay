<?php

namespace App\Models\Helper;

enum ModelEventType: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case RESTORED = 'restored';
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case REGISTER = 'register';
    case PASSWORD_REMINDER = 'password-reminder';
    case PASSWORD_CHANGED = 'password-changed';
    case OTP_ENABLED = 'otp-enabled';
    case OTP_DISABLED = 'otp-disabled';
    case EMAIL_CHANGED = 'email-changed';

}
