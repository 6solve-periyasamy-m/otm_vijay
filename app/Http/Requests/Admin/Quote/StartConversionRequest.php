<?php

namespace App\Http\Requests\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $travelling
 * @property int $paying
 * @property string|null $should_invoice
 */
class StartConversionRequest extends FormRequest
{
    public function doEmail(): bool
    {
        return $this->should_invoice == 'on';
    }

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
