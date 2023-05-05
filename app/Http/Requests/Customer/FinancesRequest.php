<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $booking_reference
 * @property float|string $amount
 */
class FinancesRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['amount' => sigfig((float)preg_replace('/[^0-9.]/', '', $this->amount))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'booking_reference' => 'required',
            'amount' => 'required|numeric|gte:0.3|lte:1000000',
        ];
    }
}
