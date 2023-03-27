<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $travel_insurer
 * @property string|null $policy_number
 * @property string|null $order_notes
 * @property string|null $order_customer_notes
 * @property string|null $accommodation_notes
 * @property string|null $activity_notes
 * @property string|null $flight_notes
 * @property string|null $transport_notes
 */
class TourDetailsRequest extends FormRequest
{
    public function getOrderCustomerDetails(): array
    {
        return [
            'travel_insurer' => $this->travel_insurer,
            'policy_number' => $this->policy_number,
            'external_notes' => $this->order_customer_notes,
            'accommodation_notes' => $this->accommodation_notes,
            'activity_notes' => $this->activity_notes,
            'flight_notes' => $this->flight_notes,
            'transport_notes' => $this->transport_notes,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [];
    }
}
