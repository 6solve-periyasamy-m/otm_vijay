<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingFlight;
use App\Models\Flight\Airport;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Tour\Tour;
use App\Repository\BookingRepository;
use App\Repository\FlightBookingRepository;
use App\Repository\FlightsRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlightController extends ApiController
{
    protected $debug = 'flights';

    public function getFlightInventories()
    {
        $flights = Flight::join('airlines', 'airline_id', 'airlines.id')
        ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
        ->get();

        return response()->json(["success" => true, "data" => $flights->toArray()]);
    }

    /**
     * getFlightInventoriesForTour
     *
     * @param [type] $tour_id
     * @param [type] $flight_type
     * @return $flights
     */
    public function getFlightInventoriesForTour($tour_id, $flight_type = null)
    {
        $flightsRepository = new FlightsRepository();
        $flights = $flightsRepository->flightsAvailableForTour($tour_id, $flight_type);
        $this->debug && Log::debug('getFlightsInventoriesForTour::', [$flights]);

        return response()->json(["success" => true, "data" => $flights]);
    }

    /**
     * getFlightsFromAirport
     *
     * @param Airport|null $airport
     * @return void
     */
    public function getFlightsFromAirport(Airport $airport = null)
    {
        // Returns a list of flights from an airport
        $flightsRepository = new FlightsRepository();
        $result = $flightsRepository->flightsDepartingAfterToday($airport);
        $this->debug && Log::info('getFlightsFromAirport::', [$result]);

        return response()->json(["success" => true, "data" => $result]);
    }

    public function addFlightInventoryToTour(Request $request, Tour $tour) {
        // TODO: Get actual enum values
        if ($request->has('type') && in_array($request->input('type'), ['Included', 'Add-on', 'Upgrade'])) {
            if ($request->has('direction') && in_array($request->input('direction'), ['Inbound', 'Outbound',])) {
                if ($request->has('ids')) {
                    foreach ($request->input('ids') as $id) {
                        $inventory = FlightInventory::findOrFail($id);
                        $inventoryTour = FlightInventoryTour::make([
                            'flight_inventory_id' => $id,
                            'tour_component_type' => $request->input('type'),
                            'tour_sales_price' => $inventory->sales_price,
                            'flight_type' => $request->input('direction'),
                        ]);
                        $this->debug && Log::info('addFlightInventoryToTour -> save', [$inventoryTour]);
                        $tour->flightInventoryTours()->save($inventoryTour);
                    }
                }
                return response('Any listed components have been successfully added', 200);
            } else {
                abort(400, 'Invalid flight direction has been provided');
            }
        }
        abort(400, 'Invalid component type has been provided');
        return null;
    }


    /**
     * loadFlightForBooking($booking_id, $type = 'Both')
     *
     * @param [type] $order_id
     * @param string $type
     * @return JSON $flightBookings
     */
    public function loadFlightsForBooking($booking_token, $type = 'Both')
    {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($booking_token);
        if (isset($booking->id)) {
            $flightBookingRepository = new FlightBookingRepository();
            $flightBookings = $flightBookingRepository->getFlightBookings($booking->id, $type);
            $this->debug && Log::debug('loadFlightsForBooking: flight bookings', [$booking->id, $flightBookings]);
            return response()->json(["success" => true, "flightBookings" => $flightBookings]);
        } else {
            return response()->json(["success" => false, "message" => "no flight bookings"]);
        }
    }
    /**
     * Remove flight booking
     *
     * @param Request $request
     * @return JSON response
     */
    public function removeFlightBooking($booking, $flightInventoryTour)
    {
        $bookingFlight = new BookingFlight();
        $booking = $bookingFlight->where('booking_id', $booking->id)
            ->where('flight_inventory_id', $flightInventoryTour->flight_inventory_id)
            ->delete();

        return $booking;
    }

    /**
     * storeOrUpdateFlightBooking
     *
     * @param [type] $booking
     * @param [type] $flight_type
     * @param [type] $flightInventoryTour
     * @return void
     */
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
            $status = $bookingFlight->save();
            Log::debug("storeOrUpdateFlightBooking", [$bookingFlight, $status]);
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
     * bookFlightDetails
     * save flight details for a pax tour flight
     * 1. create / update orders_customers
     * 2. create customer_order_details (audit)
     * 3. create / update 8orders_flights
     * @param tour
     * @param passenger
     * @param flight (we are sending in the flight->id - which should be the flightInventoryTour record id)
     */
    public function postFlightBooking(Request $request)
    {
        // validation
        $validated = $request->validate([
            'customer_id' => 'required',
            'tour_id' => 'required',
            'flight_type' => 'required',
            'flight_inventory_tour_id' => 'required',
            'custom' => 'required',
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $flight_type = $request->flight_type;
        $flight_inventory_tour_id = $request->flight_inventory_tour_id;
        $custom = $request->custom;
        $booking_token = $request->token;

        // token is posted: why look it up?  opportunity to catch a false post?
        $token = $_COOKIE['OTM_booking_token'];
        if ($token !== $booking_token) {
            throw new \Exception('Booking token mismatch');
        }

        $this->debug == 'flights' && Log::debug('postFlightBooking: validated parameters', [$customer_id, $tour_id, $flight_type, $flight_inventory_tour_id, $custom, $token]);

        $tours = new Tour();
        $rejection = 0;
        // validate parameters are valid
        if ($flight_inventory_tour_id) {
            $flightInventoryTour = $this->getFlightInventoryTour($flight_inventory_tour_id);
            if (!$flightInventoryTour) {
                $rejection = 404;
                $status = 'Invalid Flight Inventory Tour record';
            }
        } else {
            throw new Exception('ERROR: bookFlightDetails has no inventory_tour_id to book');
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
        if (isset($flightInventoryTour)) {
            $this->debug && Log::debug('postFlightBooking', [$booking, $flight_type, $flightInventoryTour]);
            $result = $this->storeOrUpdateFlightBooking($booking, $flight_type, $flightInventoryTour);
        } else {
            throw new Exception('ERROR: can not store a flight without a flightInventoryTour record');
        }

        return response()->json(['success' => $result]);
    }
}
