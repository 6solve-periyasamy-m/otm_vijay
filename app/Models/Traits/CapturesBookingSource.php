<?php

namespace App\Models\Traits;

use App\Models\Booking\Booking;
use Illuminate\Http\Request;

trait CapturesBookingSource
{
    public function captureBookingSource(): void
    {
        if (app()->runningInConsole() || !request() instanceof Request) {
            return;
        }
        if (request()->is('api/*')) {
            $this->source = request()->header('X-Booking-Source', 'web_tickets');
        } elseif (request()->is('booking/simple/*')) {
            $this->source = 'web_simple';
        } elseif (request()->is('booking/v3/*')) {
            $this->source = 'web_v3';
        } else {
            $this->source = 'unknown';
        }
        $this->source_referrer = request()->headers->get('referer');
        $this->created_ip      = request()->ip();
        $this->user_agent      = request()->userAgent();
    }
}
