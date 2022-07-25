<?php

namespace App\Http\Requests\Admin\Quote;

use App\Models\Customer\Customer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $customer_id
 * @property string $from
 * @property string $to
 * @property string $expires
 * @property string $final
 * @property string $footer
 * @property string $terms
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
            'final_payment' => $this->final,
            'invoice_footer' => $this->footer ?? "",
            'terms' => $this->terms ?? "",
            'expiry' => $this->expires,
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
        ];
    }
}
