<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\Authentication\OneTimeCodeRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property string $otp_secret
 */
class Enable2faRequest extends OneTimeCodeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'otp_secret' => 'required|string',
            ...parent::rules(),
        ];
    }
}
