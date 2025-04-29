<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use App\Models\Quote\Quote;

class BookingV3Controller extends Controller
{
    public function guest(string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $booking = Booking::where('tour_id', '=', $tour->id)->where('token', '=', $token)->first();
        if ($booking === null) {
            $booking = BookingRepository::make($tour);
            $booking->save();
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => $booking->token]);
        }
        return view('pages.customer.booking.v3.guest', ['tour' => $tour, 'booking' => $booking]);
    }

    public function hotel(string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $booking = Booking::where('tour_id', '=', $tour->id)->where('token', '=', $token)->firstOrFail();
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
        }
        return view('pages.customer.booking.v3.hotel', ['tour' => $tour, 'booking' => $booking]);
    }

    public function ticket(string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $booking = Booking::where('tour_id', '=', $tour->id)->where('token', '=', $token)->firstOrFail();
        $quote = Quote::find($booking->quote_id);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
        }
        return view('pages.customer.booking.v3.ticket', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }
}
