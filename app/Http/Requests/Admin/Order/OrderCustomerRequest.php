<?php

namespace App\Http\Requests\Admin\Order;

use App\Models\Customer\Customer;
use App\Repository\Storage\ConvertedCustomer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $customer_id
 * @property string $paying
 * @property string $travelling
 * @property float $tour_cost
 * @property float $single_occupancy_surcharge
 * @property string $travel_insurer
 * @property string $policy_number
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property string|null $accommodation_notes
 * @property string|null $activity_notes
 * @property string|null $flight_notes
 * @property string|null $transport_notes
 */
class OrderCustomerRequest extends FormRequest
{
    public function getConvertedCustomer(): ConvertedCustomer
    {
        return new ConvertedCustomer(
            Customer::find($this->customer_id),
            $this->paying == 'on',
            $this->travelling == 'on',
            $this->getData()
        );
    }

    public function getData(): array
    {
        return [
            'customer_id' => $this->customer_id,
            'tour_cost' => $this->tour_cost,
            'single_occupancy_surcharge' => $this->single_occupancy_surcharge,
            'travel_insurer' => $this->travel_insurer,
            'policy_number' => $this->policy_number,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
            'accommodation_notes' => $this->accommodation_notes,
            'activity_notes' => $this->activity_notes,
            'flight_notes' => $this->flight_notes,
            'transport_notes' => $this->transport_notes,
            'is_charged' => $this->paying == 'on',
            'is_travelling' => $this->travelling == 'on',
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
            'tour_cost' => 'required|numeric',
            'single_occupancy_surcharge' => 'required|numeric',
        ];
    }
}
