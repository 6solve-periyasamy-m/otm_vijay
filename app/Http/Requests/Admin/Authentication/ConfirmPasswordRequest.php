<?php

namespace App\Http\Requests\Admin\Authentication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $password
 * @property string|null $otp_code
 */
class ConfirmPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required',
            'otp_code' => 'nullable|numeric|digits:6',
        ];
    }
}
