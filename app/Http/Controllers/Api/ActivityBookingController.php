<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityBookingController extends Controller
{
    /**
     * updateActivityBookingBooking
     *
     * @param Request $request
     * @return void
     */
    public function updateActivityBooking(Request $request)
    {
        Log::debug('Update Activity Booking', [$request]);
        $booking = BookingActivity::where('customer_id', $request->customer_id0)


        return response()->json(['success' => true]);
    }
}
