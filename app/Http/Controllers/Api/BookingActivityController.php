<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Booking\Booking;
use App\Models\Booking\Component\BookingActivity;
use App\Models\Customer\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                ->leftJoin('activity_inventory_tour_upgrades', 'booking_activities.activity_inventory_tour_id', 'activity_inventory_tour_upgrades.upgrade_id')
                ->select(
                    'booking_activities.id as booking_activity_id',
                    'booking_activities.customer_id',
                    'booking_activities.booking_id as booking_id',
                    'booking_activities.activity_inventory_tour_id',
                    'activity_inventory_tour_upgrades.upgrade_id',
                    'activity_inventory_tour_upgrades.base_id',
                    'activity_inventory_tours.tour_component_type',
                    'activities.description',
                    'activities.image_url',
                    'activities.name',
                    'activity_inventories.sales_price',
                    'activity_inventories.starts_at',
                    'activity_inventories.ends_at',
                    'activity_inventory_tour_upgrades.description as upgrade_description'
                )
                ->where('bookings.token', $token)
                ->get();

        return response()->json(['success' => true, 'bookings' => $bookingActivities]);
    }

    public function getActivityBooking(Booking $booking, Customer $customer, int $activity_inventory_tour_id)
    {
        $bookingActivities = new BookingActivity();
        $bookingActivity = $bookingActivities
            ->where('booking_id', $booking->id)
            ->where('customer_id', $customer->id)
            ->where('activity_inventory_tour_id', $activity_inventory_tour_id)
            ->first();
        if ($bookingActivity) {
            return $bookingActivity;
        }

        return $bookingActivities;
    }

    private function checkActivityInventory($activity_inventory_tour_id)
    {
        $inventories = new ActivityInventory();
        $inventory = $inventories->join('activity_inventory_tours', 'activity_inventory_tours.activity_inventory_id', 'activity_inventories.id')
                    ->where('activity_inventory_tours.id', $activity_inventory_tour_id)
                    ->first();
        if ($inventory->stock<1) {
            return response()->json(['success' => false, 'message' => 'out of stock']);
        }
        return $inventory;
    }

    private function getBooking($token)
    {
        $bookings = new Booking();
        $booking = $bookings->where('token', $token)->first();
        if (!$booking) {
            throw new Exception('Can not find booking for '.$token);
        }
        return $booking;
    }

    public function createActivityAddonBooking(Request $request)
    {
        Log::debug('Addon Booking request', [$request->token]);
        $request->validate([
            'token' => 'required',
            'activity' => 'required',
            'customers' => 'required'
        ]);
        $activity = $request->activity;
        $activity_inventory_tour_id = $activity['activity_inventory_tour_id'];
        $this->checkActivityInventory($activity_inventory_tour_id);
        $booking = $this->getBooking($request->token);
        $booking_id = $booking->id;

        $bookings = [];
        foreach($request->customers as $customerArray) {
            $customer_id = $customerArray['id'];
            $customer = Customer::find($customer_id);
            // Log::debug('customer', [$customer_id]);
            $bookingActivity = $this->getActivityBooking($booking, $customer, $activity_inventory_tour_id);
            //Log::debug('booking_activity', [$bookingActivity]);
            if (!isset($bookingActivity->booking_id)) {
                $bookingActivity->booking_id = $booking_id;
                $bookingActivity->customer_id = $customer_id;
                $bookingActivity->activity_inventory_tour_id = $activity_inventory_tour_id;
                $bookingActivity->save();
            }
            $bookings[] = $bookingActivity;
        }
        //Log::debug('booking Addon book', $bookings);

        return response()->json(['success' => true, 'booking' => $bookings]);

    }
    public function cancelActivityAddonBooking(Request $request)
    {
        $request->validate([
            'activity' => 'required | array',
            'token' => 'required',
            'customers' => 'required | array'
        ]);
        // find the actiity booking for
        // each customer_id in customers, booking_id from token
        $bookings = Booking::where('token', $request->token)->first();
        $activity = $request->activity;
        $activity_inventory_tour_id = $activity['activity_inventory_tour_id'];
       
        $booking = [];
        $bookingActivity = new BookingActivity();
        foreach ($request->customers as $customer) {
            $booking[] = $bookingActivity->where('customer_id', $customer['id'])
                ->where('booking_id', $bookings->id)
                ->where('activity_inventory_tour_id', $activity_inventory_tour_id)
                ->first();
            $bookingActivity->where('customer_id', $customer['id'])
                ->where('booking_id', $bookings->id)
                ->where('activity_inventory_tour_id', $activity_inventory_tour_id)
                ->delete();
        }
        Log::debug('booking Addon Cancels', $booking);

        return response()->json(['success' => true, 'booking' => $booking]);
 
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
    public function createActivityUpgradeBooking(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'customers' => 'required',
            'tour_component_type' => 'string',
            'activity' => 'required'
        ]);

        $activity = $request->activity;
        $activity_inventory_tour_id = $activity['activity_inventory_tour_id'];
        // Log::debug('createActivityBooking', [$activity_inventory_tour_id]);
        $this->checkActivityInventory($activity_inventory_tour_id);
        // Log::debug('createActivityBooking: check activity_inventory stock levels', [$inventory]);
        // Log::debug('booking', [$booking]);
        $booking = $this->getBooking($request->token);
        $booking_id = $booking->id;

        $upgradeActivity = ActivityInventoryTourUpgrade::where('upgrade_id', $activity_inventory_tour_id)->first();
        $base_id = $upgradeActivity->base_id;

        $bookings = [];
        foreach($request->customers as $customerArray) {
            $customer_id = $customerArray['id'];
            $customer = Customer::find($customer_id);
            // Log::debug('customer', [$customer_id]);
            $bookingActivity = $this->getActivityBooking($booking, $customer, $activity_inventory_tour_id);
            // Log::debug('booking_activity', [$bookingActivity]);
            if (!isset($bookingActivity->booking_id)) {
                $bookingActivity->booking_id = $booking_id;
                $bookingActivity->customer_id = $customer_id;
                $bookingActivity->activity_inventory_tour_id = $activity_inventory_tour_id;
                $bookingActivity->save();
            }
            $bookings[] = $bookingActivity;
        }

        return response()->json(['success' => true, 'base_id' => $base_id, 'booking' => $bookings]);
    }

    public function bookActivityUpgradeBooking(Request $request)
    {
        $request->validate([
            'activity' => 'required | array',
            'token' => 'required',
            'customers' => 'required | array'
        ]);
        // find the actiity booking for
        // each customer_id in customers, booking_id from token
        $booking = Booking::where('token', $request->token)->first();
        $activity = $request->activity;
        $activity_inventory_tour_id = $activity['activity_inventory_tour_id'];

        $upgradeActivity = ActivityInventoryTourUpgrade::where('upgrade_id', $activity_inventory_tour_id)->first();
        $base_id = $upgradeActivity->base_id;

        $bookingActivity = new BookingActivity();
        $bookings = [];
        foreach($request->customers as $customer) {
            $bookingActivity->where('customer_id', $customer['id'])
                ->where('booking_id', $booking->id)
                ->where('activity_inventory_tour_id', $activity_inventory_tour_id)
                ->first();
            $bookingActivity->status = 'booked';
            $bookingActivity->save();
            $bookings[] = $bookingActivity;
        }
        return response()->json([
            'success' => true, 
            'base_id' => $base_id, 
            'booking' => $bookings
        ]);
    }

    public function cancelActivityUpgradeBooking(Request $request)
    {
        $request->validate([
            'activity' => 'required | array',
            'token' => 'required',
            'customers' => 'required | array'
        ]);
        // find the actiity booking for
        // each customer_id in customers, booking_id from token
        $bookings = Booking::where('token', $request->token)->first();
        $activity = $request->activity;
        $activity_inventory_tour_id = $activity['activity_inventory_tour_id'];
       
        $upgradeActivity = ActivityInventoryTourUpgrade::where('upgrade_id', $activity_inventory_tour_id)->first();
        $base_id = $upgradeActivity->base_id;
        $booking = [];
        $bookingActivity = new BookingActivity();
        foreach ($request->customers as $customer) {
            $booking[] = $bookingActivity->where('customer_id', $customer['id'])
                ->where('booking_id', $bookings->id)
                ->where('activity_inventory_tour_id', $activity_inventory_tour_id)
                ->first();
            $bookingActivity->where('customer_id', $customer['id'])
                ->where('booking_id', $bookings->id)
                ->where('activity_inventory_tour_id', $activity_inventory_tour_id)
                ->delete();
        }
        return response()->json(['success' => true, 'booking' => $booking, 'base_id' => $base_id]);
    }
    /**
     * deleteActivityBooking
     *
     * @param Request $request
     * @return void
     */
    public function DEPRECATEDdeleteActivityBooking(Request $request)
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
