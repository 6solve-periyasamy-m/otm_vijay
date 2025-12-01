<?php

namespace App\Http\Requests\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Tour\Tour;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $tour
 * @property string|null $token
 */
class SetupBookingRequest extends FormRequest
{
    public function getTour(): Tour|null
    {
        return Tour::where('booking_form_url', '=', $this->tour)->first();
    }

    public function getBooking(): Booking|null
    {
        return Booking::where('token', '=', $this->token)->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tour' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
        ];
    }
}
