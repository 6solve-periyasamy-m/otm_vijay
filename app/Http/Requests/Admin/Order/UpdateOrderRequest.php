<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $ordered_on
 * @property int|float|null $deposit
 * @property int|float|null $booking_fee
 * @property int|null $organization_id
 * @property string $invoice_footer
 * @property string $internal_notes
 * @property string $external_notes
 */
class UpdateOrderRequest extends FormRequest
{
    public function getData(): array
    {
        return [
            'ordered_on' => $this->ordered_on,
            'deposit' => $this->deposit ?? 0,
            'booking_fee' => $this->booking_fee ?? 0,
            'organization_id' => $this->organization_id,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
            'invoice_footer' => $this->invoice_footer,
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
            'ordered_on' => 'required|date',
            'deposit' => 'nullable|numeric',
            'booking_fee' => 'nullable|numeric',
            'organization_id' => 'nullable|integer|exists:organizations,id'
        ];
    }
}
