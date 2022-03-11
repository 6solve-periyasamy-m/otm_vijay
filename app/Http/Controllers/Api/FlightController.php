<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Tour;
use App\Models\Order;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\BookingFlight;

use Illuminate\Http\Request;
use App\Models\FlightInventory;
use App\Models\FlightInventoryTour;
use Illuminate\Support\Facades\Log;
use App\Repository\FlightsRepository;
use App\Http\Controllers\ApiController;
use App\Repository\BookingRepository;
use App\Repository\FlightBookingRepository;

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

    // NB: limitedvalue: replaces getFlightsFromTour which was incorect and not used
    // same as above??
    public function getFlightInventoryData()
    {
        $inventory = new FlightInventory();
        $records = $inventory->get();
        $result = $records->map(function ($flightInventory) {
            return [
                "id" => $flightInventory->id,
                "flight_id" => $flightInventory->flight->id,
                "check_in" => $flightInventory->check_in,
                "departs_at" => $flightInventory->departs_at,
                "arrives_at" => $flightInventory->arrives_at,
                "class" => $flightInventory->travelClass->name,
                "airline" => $flightInventory->flight->airline->name,
                "departure_airport" => $flightInventory->flight->departureAirport->name,
                "arrival_airport" => $flightInventory->flight->arrivalAirport->name,
            ];
        })->toArray();
        return response()->json(["success" => true, "data" => $result]);
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

        $this->debug > 3 && Log::info('flightsAvailableForTour:: DATA found'. print_r($flights->toArray(), 1));
        $this->debug && Log::info('flightsAvailableForTour:: found ' . count($flights) . ' flights available');

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
     * @return void
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

        $this->debug == 'flights' && Log::info('***** bookFlightDetails parameters:', [$customer_id, $tour_id, $flight_type, $flight_inventory_tour_id, $custom, $token]);

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
            $result = $this->storeOrUpdateFlightBooking($booking, $flight_type, $flightInventoryTour);
        } else {
            throw new Exception('ERROR: can not store a flight without a flightInventoryTour record');
        }

        return response()->json(['success' => $result]);
    }
}
