<?php

namespace App\Http\Requests\Api\Admin\Quote;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array{type: string, id: int} $components
 */
class DeleteComponentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'components.*.id' => 'required|integer|min:0',
            'components.*.type' => 'required|string'
        ];
    }
}
