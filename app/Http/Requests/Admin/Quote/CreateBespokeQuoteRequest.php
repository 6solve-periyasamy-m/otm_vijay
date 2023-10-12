<?php

namespace App\Http\Requests\Admin\Quote;

use App\Models\Customer\Customer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $customer_id
 * @property int|null $brand_id
 * @property int|null $organization_id
 * @property string $name
 * @property string|null $description
 * @property string $from
 * @property string $to
 * @property string $expires
 * @property string $final
 * @property string $footer
 * @property string $terms
 * @property string $travelling
 * @property string $paying
 * @property float|null $single_occupancy_surcharge
 * @property float $deposit
 * @property string $internal_notes
 * @property string $external_notes
 * @property float $cost
 */
class CreateBespokeQuoteRequest extends FormRequest
{
    private ?Customer $customer;

    public function getTourDetails(): array
    {
        return [
            'date_from' => $this->from,
            'date_to' => $this->to,
            'organization_id' => $this->organization_id,
            'final_payment' => $this->final,
            'invoice_footer' => $this->footer ?? "",
            'terms' => $this->terms ?? "",
            'expires' => $this->expires,
            'single_occupancy_surcharge' => $this->single_occupancy_surcharge ?? 0,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
            'name' => $this->name,
            'description' => $this->description,
            'deposit' => $this->deposit,
            'brand_id' => $this->brand_id == 0 ? null : $this->brand_id,
        ];
    }

    public function getCustomerDataset(): array
    {
        return [
            'travelling' => $this->travelling == 'on',
            'paying' => $this->paying == 'on',
        ];
    }

    public function getCustomer(): ?Customer
    {
        if (!isset($this->customer)) {
            $this->customer = Customer::find($this->customer_id);
        }
        return $this->customer;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'from' => 'required|date',
            'to' => 'required|date|after:from',
            'expires' => 'required|date',
            'final' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'single_occupancy_surcharge' => 'nullable|numeric|min:0',
            'name' => 'required',
            'organization_id' => 'nullable|integer|exists:organizations,id',
        ];
    }
}
