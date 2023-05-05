<?php

namespace App\Http\Requests\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $customer_id
 * @property int|null $brand_id
 * @property string $name
 * @property string|null $description
 * @property float $deposit
 * @property float $single_occupancy_surcharge
 * @property string $travelling
 * @property string $paying
 * @property string $from
 * @property string $to
 * @property string $final
 * @property string $expires
 * @property string $footer
 * @property string $terms
 * @property string $internal_notes
 * @property string $external_notes
 */
class QuoteEditRequest extends FormRequest
{

    public function getDataset(): array
    {
        return [
            'deposit' => $this->deposit,
            'single_occupancy_surcharge' => $this->single_occupancy_surcharge,
            'date_from' => $this->from,
            'date_to' => $this->to,
            'final_payment' => $this->final,
            'expires' => $this->expires,
            'terms' => $this->terms ?? "",
            'invoice_footer' => $this->footer,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
            'name' => $this->name,
            'description' => $this->description,
            'brand_id' => $this->brand_id > 0 ? $this->brand_id : null
        ];
    }

    public function getCustomerDataset(): array
    {
        return [
            'customer_id' => $this->customer_id,
            'paying' => $this->paying == 'on',
            'travelling' => $this->travelling == 'on',
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
            'deposit' => 'required|numeric|min:0',
            'single_occupancy_surcharge' => 'nullable|numeric|min:0',
            'from' => 'required|date',
            'to' => 'required|date',
            'final' => 'required|date',
            'expires' => 'nullable|date',
            'name' => 'required',
        ];
    }
}
