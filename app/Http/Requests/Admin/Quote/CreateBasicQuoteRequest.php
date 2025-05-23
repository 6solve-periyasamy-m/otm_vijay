<?php

namespace App\Http\Requests\Admin\Quote;

use App\Models\Customer\Customer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $customer_id
 * @property int|null $organization_id
 * @property int|null $currency_id
 * @property float $single_occupancy_surcharge
 * @property string $travelling
 * @property string $paying
 * @property string $expires
 * @property string $internal_notes
 * @property string $external_notes
 * @property int|null $brand_id
 * @property int|null $tax_bracket_id
 * @property int|null $agent_id
 * @property float $commission
 */
class CreateBasicQuoteRequest extends FormRequest
{
    public function getCustomer(): ?Customer
    {
        return Customer::find($this->customer_id);
    }

    public function getDataset(): array
    {
        $final_payment_expired = strtotime($this->final_payment) < strtotime(now());

        return [
            'expires' => $this->expires,
            'organization_id' => $this->organization_id,
            'single_occupancy_surcharge' => $this->single_occupancy_surcharge ?? 0,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
            'brand_id' => $this->brand_id,
            'tax_bracket_id' => $this->tax_bracket_id,
            'currency_id' => $this->currency_id,
            'agent_id' => $this->agent_id,
            'commission' => $this->commission,
            'final_payment' => $this->final_payment,
            'is_deposit_percentage' => $this->is_deposit_percentage,
            'deposit' => $this->deposit,
        ];
    }

    public function getCustomerDataset(): array
    {
        return [
            'travelling' => $this->travelling == 'on' || (bool)$this->travelling,
            'paying' => $this->paying == 'on'|| (bool)$this->paying,
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
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'final_payment' => 'nullable|date',
            'deposit' => 'nullable|numeric|min:0',
            'is_deposit_percentage' => 'nullable|boolean'
        ];
    }
}
