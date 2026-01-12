<?php

namespace App\Http\Requests\Booking;

class ApiDetailsRequest extends BookingOverviewRequest
{
    public function rules(): array
    {
        return [
            'lead_last_name'       => 'required|string|max:255',
            'lead_date_of_birth'   => 'nullable|date',
            'lead_mobile_number'   => 'nullable|string|max:50',
            'special_notes'   => 'nullable|string|max:500',
            ...parent::rules(),
        ];
    }
}