<?php

namespace App\Http\Requests\Admin\Authentication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $otp_code
 */
class OneTimeCodeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'otp_code' => 'required|numeric|digits:6'
        ];
    }
}
