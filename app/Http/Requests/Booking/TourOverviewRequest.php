<?php

namespace App\Http\Requests\Booking;

use App\Models\Tour\Tour;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $booking_url
 */
class TourOverviewRequest extends FormRequest
{
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
            'booking_url' => 'required',
            'currency' => 'nullable|string|max:3'
        ];
    }
}
