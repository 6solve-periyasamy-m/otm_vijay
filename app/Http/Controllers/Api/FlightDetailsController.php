<?php

// Book Flight Details Controller: BOOKING FORM Updating API
// Flight Details (refactored out of BookingController)
// TODO: move model queries to repository
// TODO: refactor private functions

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Tour;
use App\Models\Order;

use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\BookingFlight;
use App\Models\FlightInventoryTour;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;

class FlightDetailsController extends ApiController
{
    /**
     * storeOrUpdateFlightBooking
     *
     * @param [type] $booking
     * @param [type] $flightInventoryTour
     * @param [type] $flight_type
     * @param [type] $custom
     * @param [type] $token
     * @return void
     */
    private function storeOrUpdateFlightBooking($booking, $flightInventoryTour) 
    {
        $bookingFlight = new BookingFlight();
        $bookingFlight->booking_id = $booking->id;
        $bookingFlight->customer_id = $booking->customer_id;
        $bookingFlight->flight_inventory_id = $flightInventoryTour->flight_inventory_id;
        try {
            $bookingFlight->save();
        } catch (Exception $e) {
            Log::error('Error saving Booking flight' . $e->getMessage());
        }

        return $bookingFlight;
    }
    private function getFlightInventoryTour($inventory_tour_id)
    {
        $flightInventoryTour = FlightInventoryTour::find($inventory_tour_id);

        return $flightInventoryTour;
    }
    /**
     * bookFlightDetails  WIP (convert to booking tables)
     * save flight details for a pax tour flight
     * 1. create / update orders_customers
     * 2. create customer_order_details (audit)
     * 3. create / update 8orders_flights
     * @param tour
     * @param passenger
     * @param flight (we are sending in the flight->id - which should be the flightInventoryTour record id)
     */
    public function bookFlightDetails(Request $request)
    {
        // validation
        $validated = $request->validate([
            'customer_id' => 'required',
            'tour_id' => 'required',
            'flight_type' => 'required',
            'inventory_tour_id' => 'required',
            'custom' => 'required',
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $flight_type = $request->flight_type;
        $inventory_tour_id = $request->inventory_tour_id;
        $custom = $request->custom;
        $booking_token = $request->token;

        // token is posted: why look it up?  opportunity to catch a false post?
        $token = $_COOKIE['OTM_booking_token'];
        if ($token !== $booking_token) {
            throw new \Exception('Booking token mismatch');
        }

        $this->logging == 'flights' && Log::info('***** bookFlightDetails parameters:', [$customer_id, $tour_id, $order_id, $flight_type, $inventory_tour_id, $custom, $token]);

        $tours = new Tour();
        $rejection = 0;

        // validate parameters are valid
        if ($inventory_tour_id) {
            $flightInventoryTour = $this->getFlightInventoryTour($inventory_tour_id);
            if (!$flightInventoryTour) {
                $rejection = 404;
                $status = 'Invalid Flight Inventory Tour record';
            }
        } else {
            throw new Exception('Booking Flight Details has no inventory_tour_id to book');
        }
        $tour = $tours->find($tour_id);
        $booking = Booking::where('token', $booking_token)->first();
        if (empty($booking)) {
            throw new Exception('booking could not be found for token '.$token);
        }
        $status = '';

        if ($tour->id !== $booking->tour_id) {
            throw new Exception('BookFlightDetails: booking and tour ids do not agree');
        }
        if ($customer_id !== $booking->customer_id) {
            throw new Exception('BookFlightDetails: booking and customer IDs do not agree');
        }
Log::debug('flightInventoryTour', [$flightInventoryTour]);
        if ($inventory_tour_id) {
            $result = $this->storeOrUpdateFlightBooking($booking, $flightInventoryTour);
            //$result = $this->storeOrUpdateCustomerOrderDetail($orderCustomer, $flightTour, $flight_type, $custom, $token);
            $this->logging && Log::info('storeOrUpdateCustomerOrderDetail returned!', [$result]);
        } else {
            $result = $this->removeFlightBooking($booking, $flightInventoryTour);
            $this->logging && Log::info('removeCustomerOrderDetail');
        }

        return response()->json(['success' => $result]);
    }

    /**
     * Remove flight booking
     *
     * @param Request $request
     * @return JSON response
     */
    private function removeFlightBooking($booking, $flightInventoryTour)
    {
        $bookingFlight = new BookingFlight();
        $booking = $bookingFlight->where('booking_id', $booking->id)
            ->where('flight_inventory_id', $flightInventoryTour->flight_inventory_id)
            ->delete();
        
        return $booking;
    }
}
