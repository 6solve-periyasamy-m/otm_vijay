<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $currency
 * @property float|null $surcharge
 */
class SetSurchargeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'currency' => 'required|string|max:3',
            'surcharge' => 'nullable|numeric|min:0',
        ];
    }
}
