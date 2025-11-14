<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Location\Currency;
use App\Models\Tour\Tour;
use Illuminate\Http\Request;

class SimpleBookingController extends Controller
{
    public function index(Request $request, string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        if ($token !== null) {
            $booking = $tour->bookings()->where('token', '=', $token)->firstOrFail();
        } else if ($request->currency !== null && in_array($request->currency, BookingV3Controller::ALLOWED_CURRENCIES)) {
            $currency = Currency::fromCode($request->currency) ?? Currency::fromCode('USD');
        }
        return view('customer.booking.simple.index', ['tour' => $tour, 'booking' => $booking ?? null, 'currency' => $currency ?? null]);
    }

    public function checkout(string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $booking = $tour->bookings()->where('token', '=', $token)->firstOrFail();
        return view('customer.booking.simple.checkout', ['tour' => $tour, 'booking' => $booking,]);
    }
}
