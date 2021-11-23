<?php

namespace App\Http\Controllers\Api;

use App\Models\Tour;
use App\Models\Order;
use App\Models\Flight;
use App\Models\Airport;

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
    protected $logging = 1;

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

        if ($this->logging > 3) {
            Log::info('flightsAvailableForTour:: DATA found'. print_r($flights->toArray(), 1));
        } else if ($this->logging > 0) {
            Log::info('flightsAvailableForTour:: found ' . count($flights) . ' flights available');
        }

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
            if ($request->has('ids')) {
                foreach ($request->input('ids') as $id) {
                    $inventory = FlightInventory::findOrFail($id);
                    $inventoryTour = FlightInventoryTour::make([
                        'flight_inventory_id' => $id,
                        'tour_component_type' => $request->input('type'),
                        'tour_sales_price' => $inventory->sales_price,
                    ]);
                    $tour->flightInventoryTours()->save($inventoryTour);
                }
            }
            return response('Any listed components have been successfully added', 200);
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

        $flightBookingRepository = new FlightBookingRepository();
        $flightBookings = $flightBookingRepository->getFlightBookings($booking->id. $type);

        return response()->json(["success" => true, "flightBooking" => $flightBookings]);
    }
}

class OLD_FLIGHT_CONTROLLER {
    /**
     * loadFlightsForOrder: DEPRECATE
     * what has been booked: Outbound, Inbound or Both for an ORDER_ID
     *
     * @param [type] $order_id
     * @return array
     */
    public function loadFlightsForOrder($order_id, $type = 'Both') {
        Log::debug('loadFlightsForOrder::deprecated function call');
                $order = new Order();
                $orders = $order
                    ->select('customer_order_details.*','customer_order_details.id as cod_id', 'flight_inventory_tours.*', 'flights.*')
                    ->join('order_customers', 'order_customers.order_id', 'order_id')
                    ->join('customer_order_details', 'customer_order_details.order_customer_id', 'order_customers.id')
                    ->join('flight_inventory_tours', 'flight_inventory_tours.id', 'customer_order_details.inventory_tour_id')
                    ->join('flight_inventories','flight_inventories.id', 'flight_inventory_tours.flight_inventory_id')
                    ->join('flights', 'flights.id', 'flight_inventories.flight_id')
                    ->leftJoin('airlines', 'airlines.id', 'flights.airline_id')
                    ->where('orders.id', $order_id)
                    ->whereNull('customer_order_details.deleted_at');
                    if ($type == 'Both') {
                        $orders = $orders->whereIn('customer_order_details.type', ['Outbound', 'Inbound']);
                    } else {
                        $orders = $orders->where('customer_order_details.type', $type);
                    }
                    $orders = $orders->get();
        
                // left joins for airports requires queries as they are a pair
                foreach($orders as &$ord) {
                    $ord['departure_airport'] = Airport::find($ord->departure_airport_id)->name;
                    $ord['arrival_airport'] = Airport::find($ord->arrival_airport_id)->name;
                }
                if ($this->logging) {
                    Log::info('loadFlightsForOrder order '. $order_id . ' found '. count($orders). ' orders');
                }
        
                return response()->json(["success" => true, "orders" => $orders]);
            }
}
