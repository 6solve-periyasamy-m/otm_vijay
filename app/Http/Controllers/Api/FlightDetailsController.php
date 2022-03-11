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
     * @param [type] $flight_type
     * @param [type] $flightInventoryTour
     * @return void
     */
    private $logging = 'flights';

    private function storeOrUpdateFlightBooking($booking, $flight_type, $flightInventoryTour)
    {
        $bookingFlight = new BookingFlight();
        $existing = $bookingFlight->where('flight_type', $flight_type)
            ->where('booking_id', $booking->id)
            ->where('customer_id', $booking->customer_id)
            ->first();

        if (empty($existing)) {
            $bookingFlight->booking_id = $booking->id;
            $bookingFlight->customer_id = $booking->customer_id;
            $bookingFlight->flight_type = $flight_type;
            if (isset($flightInventoryTour)) {
                $bookingFlight->flight_inventory_tour_id = $flightInventoryTour->id;
            } else {
                throw new Exception('can not store a new flight booking with the flightInventoryTour (id)');
            }
        } else {
            $bookingFlight = $existing;
            $bookingFlight->flight_inventory_tour_id = $flightInventoryTour->id;
        }
        
        try {
            $bookingFlight->save();
        } catch (Exception $e) {
            Log::error('Error saving Booking flight' . $e->getMessage());
        }

        return $bookingFlight;
    }

    private function getFlightInventoryTour($inventory_tour_id)
    {
        $flightInventoryTour = FlightInventoryTour::where('id', $inventory_tour_id)->whereNull('deleted_at')->first();
        if (empty($flightInventoryTour) || $flightInventoryTour->count() === 0) {
            throw new Exception('no Flight InventoryTour record for '.$inventory_tour_id);
        }

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
    public function bookFlights(Request $request)
    {
Log::debug('FlightDetailsController::bookFlights called');
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

