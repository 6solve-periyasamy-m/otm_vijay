<?php

namespace App\Http\Requests\Admin\Quote;

use App\Models\Customer\Customer;
use Illuminate\Foundation\Http\FormRequest;


/**
 * @property int $customer_id
 * @property string $travelling
 * @property string $paying
 * @property string $expires
 * @property string $internal_notes
 * @property string $external_notes
 */
class CreateBasicQuoteRequest extends FormRequest
{
    public function getCustomer(): ?Customer
    {
        return Customer::find($this->customer_id);
    }

    public function getDataset(): array
    {
        return [
            'expires' => $this->expires,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
        ];
    }

    public function getCustomerDataset(): array
    {
        return [
            'travelling' => $this->travelling == 'on',
            'paying' => $this->paying == 'on',
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
            'customer_id' => 'required|exists:customers,id',
            'expires' => 'required|date',
        ];
    }
}
