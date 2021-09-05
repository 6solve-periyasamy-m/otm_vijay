<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Tour;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Order;
use App\Models\CustomerOrderDetail;
use App\Models\FlightInventory;
use App\Models\OrdersCustomer;

class FlightController extends ApiController
{
    protected $logging = 5;

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
                "check_in_date_time" => $flightInventory->check_in_date_time,
                "departure_date_time" => $flightInventory->departure_date_time,
                "arrival_date_time" => $flightInventory->arrival_date_time,
                "class" => $flightInventory->travelClass->title,
                "airline" => $flightInventory->flight->airline->airline_name,
                "departure_airport" => $flightInventory->flight->departureAirport->airport_name,
                "arrival_airport" => $flightInventory->flight->arrivalAirport->airport_name,
            ];
        })->toArray();
        return response()->json(["success" => true, "data" => $result]);
    }

    /***
     * getFlightInventoriesForTour: what flights are available for this tour
     * flights booked for a tour create records in the flight_inventory_tours table
     * these associate a flight_inventory_id with a tour_id (so the tour booking creates these)
     */
    public function getFlightInventoriesForTour($tour_id, $flight_type = null)
    {
        $flight = new Flight();
        // $flightsRepository = new FlightsRepository($flight);
        // $flights = $flightsRepository->flights($tour_id);

        $flights = Flight::select('flight_inventories.*', 'flight_inventory_tour.id as flight_inventory_tour_id', 'flight_inventory_tour.flight_type', 'flights.departure_airport_id', 'flights.arrival_airport_id', 'airlines.airline_name', 'travel_classes.title as travel_class', 'flights.available_after')
        ->join('airlines', 'airline_id', 'airlines.id')
        ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
        ->join('travel_classes', 'flight_inventories.travel_class_id', 'travel_classes.id')
        ->join('flight_inventory_tour', 'flight_inventory_tour.flight_inventory_id', 'flight_inventories.id')
        ->where('flight_inventory_tour.tour_id', $tour_id)
        ->where(function($q) {
            $q->whereNull('flights.available_after')
                ->orWhere('flights.available_after', '<', date('Y-m-d'));
        });

        if (isset($flight_type) && strlen($flight_type)) {
            $flights = $flights->where('flight_inventory_tour.flight_type', $flight_type);
        } else {
            $flights = $flights->whereIn('flight_inventory_tour.flight_type', ['Outbound', 'Inbound'])
            ->orderBy('flight_inventory_tour.flight_type', 'desc');
        }
        
        $flightData = $flights
            ->orderBy('airlines.airline_name', 'asc')
            ->get();
        
        if ($this->logging > 5) {
            Log::info("\n".'getFlightInventoriesForTour:: flights  after:'.date('Y-m-d'). ' type:' . $flight_type .' tour_id:'.  $tour_id . ' : '. $flights->toSql());
        } else if ($this->logging > 3) {
            Log::info('getFlightInventoriesForTour:: DATA found'. print_r($flightData->toArray(), 1));
        } else if ($this->logging > 0) {
            Log::info('getFlightInventoriesForTour:: found ' . count($flightData) . ' flights available');
        }

        return response()->json(["success" => true, "data" => $flightData->toArray()]);
    }

    public function getFlightsFromAirport(Airport $airport = null)
    {
        // Returns a list of flights from an airport
        $today = date('Y-m-d');
        $flights = Flight::where('departure_airport_id', $airport->id)
        ->orWhere(function ($query) {
            $query->whereNull('departure_date')
                ->where(DB::raw("(STR_TO_DATE(flights.departure_date,'%y-%m-%d'))"), ">=", date('Y-m-d'));
        })
        ->get();
        $result = $flights->map(function ($flight) {
            return [
            "id" => $flight->id,
            "departure_airport_id" => $flight->departure_airport_id,
            "departure_date" => $flight->departure_date,
            "arrival_airport_id" => $flight->arrival_airport_id,
            "arrival_date" => $flight->arrival_date,
        ];
        })->toArray();

        return response()->json(["success" => true, "data" => $result]);
    }

    /**
     * loadFlightsForOrder: 
     * what has been booked: Outbound, Inbound or Both for an ORDER_ID
     *
     * @param [type] $order_id
     * @return array
     */
    public function loadFlightsForOrder($order_id, $type = 'Both') {
        $order = new Order();
        $orders = $order
            ->select('customer_order_details.*','customer_order_details.id as cod_id', 'flight_inventory_tour.*', 'flights.*')
            ->join('orders_customers', 'orders_customers.order_id', 'order_id')
            ->join('customer_order_details', 'customer_order_details.orders_customer_id', 'orders_customers.id')
            ->join('flight_inventory_tour', 'flight_inventory_tour.id', 'customer_order_details.inventory_tour_id')
            ->join('flight_inventories','flight_inventories.id', 'flight_inventory_tour.flight_inventory_id')
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
            $ord['departure_airport'] = Airport::find($ord->departure_airport_id)->airport_name;
            $ord['arrival_airport'] = Airport::find($ord->arrival_airport_id)->airport_name;
        }
        if ($this->logging) {
            Log::info('loadFlightsForOrder order '. $order_id . ' found '. count($orders). ' orders');
        }

        return response()->json(["success" => true, "orders" => $orders]);
    }
}
