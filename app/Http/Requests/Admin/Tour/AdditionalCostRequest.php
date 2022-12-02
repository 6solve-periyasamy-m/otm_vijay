<?php

namespace App\Http\Requests\Admin\Tour;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property bool $per_customer
 * @property float $amount
 */
class AdditionalCostRequest extends FormRequest
{
    public function getData()
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'per_customer' => (bool)$this->per_customer
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'per_customer' => 'required|boolean'
        ];
    }
}
