<?php

namespace App\Http\Requests\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $travelling
 * @property int $paying
 */
class StartConversionRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'travelling' => 'required|integer|min:0',
            'paying' => 'required|integer|min:0',
        ];
    }
}
