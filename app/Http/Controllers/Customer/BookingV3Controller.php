<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Location\Currency;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use Cookie;
use Illuminate\Http\Request;

class BookingV3Controller extends Controller
{
    public const ALLOWED_CURRENCIES = ['AUD', 'USD', /* 'EUR', 'GBP', */];

    public function guest(Request $request, string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $booking = $this->getBooking($tour, $booking);
        if ($booking === null) {
            $booking = BookingRepository::make($tour);
            if ($request->currency !== null && in_array(strtoupper($request->currency), self::ALLOWED_CURRENCIES)) {
                $booking->currency_id = Currency::where('code', '=', $request->currency)->first()?->id;
            }
            // As requested, default back to USD
            if ($booking->currency_id === null) {
                $booking->currency_id = Currency::where('code', '=', 'USD')->first()?->id;
            }
            $booking->save();
            $this->setupCookie($tour, $booking);
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => $booking->token]);
        }
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.guest', ['tour' => $tour, 'booking' => $booking]);
    }

    public function hotel(Request $request, string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $booking ?? $request->cookie($tour->booking_form_url);
        $booking = $this->getBooking($tour, $token);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null, 'currency' => $request->currency,]);
        }
        $this->setupCookie($tour, $booking);
        if ($tour->accommodationInventoryTours()->count() === 0) {
            return redirect()->route('booking.v3.tickets', ['tour' => $tour->booking_form_url, 'booking' => $booking->token]);
        }

        return view('pages.customer.booking.v3.hotel', ['tour' => $tour, 'booking' => $booking]);
    }

    public function ticket(Request $request, string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $booking ?? $request->cookie($tour->booking_form_url);
        $booking = $this->getBooking($tour, $token);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null, 'currency' => $request->currency,]);
        }
        $quote = Quote::find($booking->quote_id);
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.ticket', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }

    public function inclusion(Request $request, string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $booking ?? $request->cookie($tour->booking_form_url);
        $booking = Booking::where('tour_id', '=', $tour->id)->where('token', '=', $token)->firstOrFail();
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null, 'currency' => $request->currency,]);
        }
        $quote = Quote::find($booking->quote_id);
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.inclusion', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }

    public function details(Request $request, string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        $token = $booking ?? $request->cookie($tour->booking_form_url);
        $booking = $this->getBooking($tour, $token);
        if ($booking === null) {
            return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null, 'currency' => $request->currency,]);
        }
        $quote = Quote::find($booking->quote_id);
        $this->setupCookie($tour, $booking);
        return view('pages.customer.booking.v3.details', ['tour' => $tour, 'booking' => $booking, 'quote' => $quote]);
    }

    public function reset(string $tour, string|null $booking = null)
    {
        $tour = Tour::where('booking_form_url', '=', $tour)->firstOrFail();
        Booking::where('tour_id', '=', $tour->id)->where('token', '=', $booking)->first()?->repository->forceDelete();
        return redirect()->route('booking.v3.guest', ['tour' => $tour->booking_form_url, 'booking' => null, 'currency' => $request->currency,]);
    }

    private function setupCookie(Tour $tour, Booking $booking): void
    {
        // Disabled for now. Consideration for later
        return;
        // Place cookie for 12hrs
        Cookie::queue(Cookie::make($tour->booking_form_url, $booking->token, 12 * 60));
    }

    private function getBooking(Tour $tour, string|null $token): Booking|null
    {
        if ($token === null) { return null;}
        $booking = Booking::where('tour_id', '=', $tour->id)
            ->where('token', '=', $token)
            ->first();
        if ($booking !== null &&
            ($booking->last_renewed ?? $booking->created_at)->addMinutes(setting('booking.expiry', Booking::DEFAULT_EXPIRY))->gt(now())) {
            return $booking;
        }
        return null;
    }
}
