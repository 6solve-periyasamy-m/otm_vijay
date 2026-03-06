<?php

namespace App\Models\Traits;

use App\Models\Booking\Booking;
use Illuminate\Http\Request;
use App\Repository\Facades\EventLogger;

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

        //  SAVE LOCATION HERE
       //  $location = EventLogger::getLocationByIp('59.92.106.79');
        $location = EventLogger::getLocationByIp($this->created_ip) ?? [];
        $this->visitor_info = implode(',', [
            $location['countryCode'] ?? '',
            $location['countryName'] ?? '',
            $location['state'] ?? '',
            $location['city'] ?? '',
            $location['timezone'] ?? '',
        ]);
    }
}
