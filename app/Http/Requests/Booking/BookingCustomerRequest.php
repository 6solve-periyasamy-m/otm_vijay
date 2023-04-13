<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $lead_title
 * @property-read string $lead_first_name
 * @property-read string|null $lead_middle_names
 * @property-read string $lead_last_name
 * @property-read string $lead_date_of_birth
 * @property-read string $lead_email_address
 * @property-read string $lead_mobile_number
 * @property-read string $lead_home_address_line_1
 * @property-read string|null $lead_home_address_line_2
 * @property-read string|null $lead_home_town
 * @property-read string|null $lead_home_region
 * @property-read int $lead_home_country
 * @property-read string $lead_home_postcode
 * @property-read string $lead_billing_address_line_1
 * @property-read string|null $lead_billing_address_line_2
 * @property-read string|null $lead_billing_town
 * @property-read string|null $lead_billing_region
 * @property-read int $lead_billing_country
 * @property-read string $lead_billing_postcode
 * @property-read int $lead_room_type
 * @property-read string $lead_group
 * @property-read int $outbound
 * @property-read int $inbound
 * @property-read array[]|null $additional
 */
class BookingCustomerRequest extends FormRequest
{
    /**
     * Get an Array of all the lead traveller details
     * 
     * @return array
     */
    public function getLeadTravellerDetails(): array
    {
        return [
            'title' => $this->lead_title,
            'first_name' => $this->lead_first_name,
            'middle_names' => $this->lead_middle_names,
            'last_name' => $this->lead_last_name,
            'email_address' => $this->lead_email_address,
            'date_of_birth' => $this->lead_date_of_birth,
            'mobile_number' => $this->lead_mobile_number,
            'home_address_line_1' => $this->lead_home_address_line_1,
            'home_address_line_2' => $this->lead_home_address_line_2,
            'home_town' => $this->lead_home_town,
            'home_region' => $this->lead_home_region,
            'home_country' => $this->lead_home_country,
            'home_postcode' => $this->lead_home_postcode,
            'billing_address_line_1' => $this->lead_billing_address_line_1,
            'billing_address_line_2' => $this->lead_billing_address_line_2,
            'billing_town' => $this->lead_billing_town,
            'billing_region' => $this->lead_billing_region,
            'billing_country' => $this->lead_billing_country,
            'billing_postcode' => $this->lead_billing_postcode,
            'room_type_id' => $this->lead_room_type,
            'group_id' => $this->lead_group,
        ];
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'lead_title' => 'required',
            'lead_first_name' => 'required',
            'lead_last_name' => 'required',
            'lead_date_of_birth' => 'required',
            'lead_email_address' => 'required|confirmed',
            'lead_mobile_number' => 'required',
            'lead_home_address_line_1' => 'required',
            'lead_home_country' => 'required|exists:countries,id',
            'lead_home_postcode' => 'required',
            'lead_billing_address_line_1' => 'required',
            'lead_billing_country' => 'required|exists:countries,id',
            'lead_billing_postcode' => 'required',
            'additional.*.title' => 'required',
            'additional.*.first_name' => 'required',
            'additional.*.last_name' => 'required',
            'additional.*.date_of_birth' => 'nullable',
            'additional.*.email_address' => 'nullable|email',
        ];
    }
}
