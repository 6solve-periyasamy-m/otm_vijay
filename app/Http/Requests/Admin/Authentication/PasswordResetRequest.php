<?php

namespace App\Http\Requests\Admin\Authentication;

use Illuminate\Validation\Rules\Password;

/**
 * @property string $password
 * @property string $password_confirmation
 */
class PasswordResetRequest extends ReceivedResetRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ];
    }
}
