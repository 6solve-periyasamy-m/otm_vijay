<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class CustomerResetPassword extends ResetPassword
{
    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('customer.password.reset', [
            'token' => $this->token,
            'email_address' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
