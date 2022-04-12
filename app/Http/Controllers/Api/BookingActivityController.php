<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BookingActivity;
use App\Http\Controllers\ApiController;

class BookingActivityController extends ApiController
{
    /**
     * getActivitesForBooking
     *
     * @param [type] $token
     * @return JSON response collection of bookingActivities
     */
    public function getActivitiesForBooking($token)
    {
        $bookingActivities = BookingActivity::join('bookings', 'booking_activities.booking_id', 'bookings.id')
                ->where('token', $token)
                ->get();

        return response()->json(['success' => true, 'data' => $bookingActivities]);
    }
    public function getActivitiesForCustomer($token, Customer $customer)
    {
        $bookingActivities = BookingActivity::join('bookings', 'booking_activities.booking_id', 'bookings.id')
                ->where('token', $token)
                ->where('booking_activities.customer_id', $customer->id)->get();

        return response()->json(['success' => true, 'data' => $bookingActivities]);
    }

    private function findBookingActivity(Request $request)
    {
        // get the booking
        $booking = Booking::where('token', $request->token)->first();
        $activity = Activity::where('id', $request->activity_id)->first();
        $customer = Customer::where('id', $request->customer_id)->first();
        // bookingActivity
        $bookingActivity = null;
        if ($booking && $activity && $customer) {
            $bookingActivity = BookingActivity::where('bookings_id', $booking->id)
                ->where('activities_id', $activity->id)
                ->where('customer_id', $customer->id)
                ->first();
        }
        return $bookingActivity;
    }
    /**
     * createBookingActivityForCustomer
     *
     * @param Request $request [token, customer, activity]
     * @return JSON response
     */
    public function createBookingActivityForCustomer(Request $request)
    {
        $bookingActivity = $this->findBookingActivity($request);
        if (empty($bookingActivity)) {
            $bookingActivity = new BookingActivity();
            $bookingActivity->booking_id = $booking->id;
            $bookingActivity->customer_id = $customer->id;
            $bookingActivity->activity_id = $activity->id;
            $bookingActivity->save();
        }

        return response()->json(['success' => true, 'record' => $bookingActivity]);        
    }

    /**
     * removeActivityBookingForCustomer
     *
     * @param Request $request [token, customer, activity]
     * @return JSON response
     */
    public function removeActivityBookingForCustomer(Request $request)
    {
        $bookingActivity = $this->findBookingActivity($request);
        if ($bookingActivity) {
            $bookingActivity->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'not found']);
    }
}
