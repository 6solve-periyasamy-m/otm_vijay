<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Tour\Tour;

class SimpleBookingController extends Controller
{
    public function index(string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        if ($token !== null) {
            $booking = $tour->bookings()->where('token', '=', $token)->firstOrFail();
        }
        return view('customer.booking.simple.index', ['tour' => $tour, 'booking' => $booking ?? null]);
    }

    public function purchase()
    {

    }
}
