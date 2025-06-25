<?php

namespace App\Http\Requests\Api\Tour;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $package_name
 * @property string|null $currency
 */
class TourCostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Implement Auth in Future
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'package_name' => 'required',
            'currency' => 'nullable|string|min:3|max:3',
        ];
    }
}
