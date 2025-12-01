<?php

namespace App\Http\Requests;

use App\Models\Booking\Booking;
use App\Models\Tour\Tour;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingOverviewRequest extends FormRequest
{
    public function getBooking(): Booking|null
    {
        return Booking::where('token', '=', $this->token)->first();
    }

    public function getTour(): Tour|null
    {
        return Tour::where('booking_form_url', '=', $this->booking_url)->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'booking_url' => 'required|string',
        ];
    }
}
