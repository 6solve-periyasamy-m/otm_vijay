<?php

namespace App\Models\Traits;

use App\Models\Booking\Booking;
use Illuminate\Http\Request;

trait CapturesBookingSource
{
    protected function captureBookingSource(Request $request, Booking $booking,string $defaultSource): void 
    {
        if (!empty($booking->source)) { return; }
        $booking->source = $request->header(
            'X-Booking-Source',
            $defaultSource
        );
        $booking->created_ip = $request->ip();
        $booking->user_agent = substr((string) $request->userAgent(), 0, 255);
        $booking->source_referrer = $request->headers->get('referer');
    }
}
