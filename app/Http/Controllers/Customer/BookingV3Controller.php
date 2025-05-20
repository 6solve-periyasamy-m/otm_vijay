<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use Cookie;
use Illuminate\Http\Request;

class BookingV3Controller extends Controller
{
    public function guest(Request $request, string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $booking = $this->getBooking($tour, $booking);
        if ($booking === null) {
            $booking = BookingRepository::make($tour);
            $booking->save();
            $this->setupCookie($tour, $booking);
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => $booking->token]);
        }
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.guest', ['tour' => $tour, 'booking' => $booking]);
    }

    public function hotel(Request $request, string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $token ?? $request->cookie($tour->booking_form_url);
        $booking = $this->getBooking($tour, $token);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
        }
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.hotel', ['tour' => $tour, 'booking' => $booking]);
    }

    public function ticket(Request $request, string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $token ?? $request->cookie($tour->booking_form_url);
        $booking = $this->getBooking($tour, $token);
        $quote = Quote::find($booking->quote_id);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
        }
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.ticket', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }

    public function inclusion(Request $request, string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $token ?? $request->cookie($tour->booking_form_url);
        $booking = Booking::where('tour_id', '=', $tour->id)->where('token', '=', $token)->firstOrFail();
        $quote = Quote::find($booking->quote_id);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
        }
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.inclusion', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }

    public function details(Request $request, string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $token ?? $request->cookie($tour->booking_form_url);
        $booking = $this->getBooking($tour, $token);
        $quote = Quote::find($booking->quote_id);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
        }
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.details', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }

    public function reset(string $tour, string|null $token = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        Booking::where('tour_id', '=', $tour->id)->where('token', '=', $token)->first()?->repository->forceDelete();
        return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null]);
    }

    private function setupCookie(Tour $tour, Booking $booking): void
    {
        // Disabled for now. Consideration for later
        return;
        // Place cookie for 12hrs
        Cookie::queue(Cookie::make($tour->booking_form_url, $booking->token, 12 * 60));
    }

    private function getBooking(Tour $tour, string $token): Booking|null
    {
        $booking = Booking::where('tour_id', '=', $tour->id)
            ->where('token', '=', $token)
            ->first();
        if ($booking !== null &&
            ($booking->last_renewed ?? $booking->created_at)->addMinutes(setting('booking.expiry', Booking::DEFAULT_EXPIRY))->lt(now())) {
            return $booking;
        }
        return null;
    }
}
