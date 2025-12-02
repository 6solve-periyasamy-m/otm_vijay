<?php

namespace App\Http\Requests;

use App\Models\Booking\Booking;
use App\Models\Tour\Tour;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

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

    public function validatePackage(): JsonResponse|bool
    {
        $tour = $this->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'A tour with that URL does not exist.',], 422);
        }
        $booking = $this->getBooking();
        if ($booking === null) {
            return response()->json(['success' => false, 'message' => 'A booking with that token does not exist.',], 422);
        }
        if ($booking->tour_id !== $tour->id) {
            return response()->json(['success' => false, 'message' => 'The token does not match this tour',], 422);
        }
        return true;
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
