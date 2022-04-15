<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BookingActivity;
use Illuminate\Support\Facades\Log;
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
                ->join('activity_inventory_tours', 'activity_inventory_tours.id', 'booking_activities.activity_inventory_tour_id')
                ->join('activity_inventories', 'activity_inventory_tours.activity_inventory_id', 'activity_inventories.id')
                ->join('activities', 'activity_inventories.activity_id', 'activities.id')
                ->leftJoin('activity_inventory_tour_upgrades', 'activity_inventories.id', 'activity_inventory_tour_upgrades.upgrade_id')
                ->select(
                    'activity_inventory_tour_upgrades.base_id',
                    'activity_inventory_tour_upgrades.upgrade_id',
                    'activities.description',
                    'activities.image_url',
                    'activities.name',
                    'activity_inventories.starts_at',
                    'activity_inventories.ends_at',
                    'activity_inventory_tour_upgrades.description as upgrade_description'
                )
                ->where('token', $token)
                ->get();
        Log::debug('bookingActivities:', [$bookingActivities]);

        return response()->json(['success' => true, 'data' => $bookingActivities]);
    }

    /**
     * createActivityBooking
     *
     * @param Request $request
     *          $booking_id
     *          $customer_id
     *          $tour_component_type (is not used)
     *          $activity_inventory_tour_id
     * @return JSON success boolean
     */
    public function createActivityBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required | int | exists:bookings, id',
            'customer_id' => 'required | int | exists:customers, id',
            'tour_component_type' => 'string',
            'activity_inventory_tour_id' => 'requried | int | exists:activity_inventory_tours, id'
        ]);
        $inventories = new ActivityInventory();
        $inventory = $inventories->join('activity_inventory_tours', 'activity_inventory_tours.activity_inventory_id', 'activity_inventory_tours.id')
                    ->where('activity_inventory_tour_id', $request->activity_inventory_tour_id)
                    ->first();
        Log::debug('createActivityBooking: check activity_inventory stock levels', [$inventory]);
        if ($inventory->stock<1) {
            return response()->json(['success' => false, 'message' => 'out of stock']);
        }

        $bookingActivity = new BookingActivity();
        $bookingActivity->booking_id = $request->booking_id;
        $bookingActivity->customer_id = $request->customer_id;
        $bookingActivity->activity_inventory_tour_id = $request->activity_inventory_tour_id;
        $bookingActivity->save();

        return response->json(['success' => true]);
    }

    /**
     * deleteActivityBooking
     *
     * @param Request $request
     * @return void
     */
    public function deleteActivityBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required | int | exists:bookings, id',
            'customer_id' => 'required | int | exists:customers, id',
            'tour_component_type' => 'string',
            'activity_inventory_tour_id' => 'requried | int | exists:activity_inventory_tours, id'
        ]);
        try {
            $bookingActivity = new BookingActivity();
            $bookingActivity->where('booking_id', $request->booking_id)
                ->delete();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}

class deprecatedstuffforactivitybooking {
    /** TODO: check:  initial versions of the above */
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
