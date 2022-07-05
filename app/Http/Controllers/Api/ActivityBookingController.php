<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking\Component\BookingActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityBookingController extends Controller
{
    /**
     * getActivityBookingState
     *
     * @param Request $request 
     *          $activity 
     * @return boolean (JSON)
     */
    public function getActivityBookingStatus(String $token)
    {
        $bookingActivities = BookingActivity::join('bookings', 'bookings.id', 'booking_activities.booking_id')
            ->where('bookings.token', $token)
            ->get();

        Log::debug('getActivityBookingstate', [$bookingActivities]);
    }

    /**
     * updateActivityBookingBooking
     *
     * @param Request $request
     * @return void
     */
    public function updateActivityBooking(Request $request)
    {
        Log::debug('Update Activity Booking', [$request]);
        // $booking = BookingActivity::where('customer_id', $request->customer_id)


        return response()->json(['success' => true]);
    }
}
