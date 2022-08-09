<?php

namespace App\Http\Requests\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $quantity
 * @property float $cost
 */
class QuotePricePointRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0.01'
        ];
    }
}
