<?php

namespace App\Repository;

use Exception;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

interface FlightsRepositoryInterface {
    // all flights for a tour
    public function getFlights($tour_id);
    // flights (optionally of a type), available after today
    public function flightsAvailableForTour($tour_id, $flight_type);
}

class FlightsRepository implements FlightsRepositoryInterface
{
    protected $model;
    private $logging = false;

    public function __construct()
    {
        $this->model = new Flight();
    }

    public function getFlights($tour_id) 
    {

        $flights = $this->model->join('airlines', 'airline_id', 'id')
            ->join('flight_inventory_tours', 'tour_id', $tour_id)
            ->join('flight_inventories', 'flight_inventory_tours.flight_inventory_id', 'id')
            ->where('flights.tour_id', $tour_id)
            ->orderBy('airlines.name')
            ->get();

        return $flights;
    }

    public function flightsAvailableForTour($tour_id, $flight_type, $tour_component_type = 'Included')
    {
        $flights = Flight::select('flight_inventory_tours.id as flight_id', 
            'flight_inventory_tours.tour_component_type', 
            'airlines.name as airline_name', 
            'flight_inventories.*', 
            'flight_inventory_tours.id as flight_inventory_tour_id', 'flight_inventory_tours.flight_type', 
            'flights.departure_airport_id', 'flights.arrival_airport_id', 
            'airlines.name', 
            'travel_classes.name as travel_class', 
            'flight_inventory_tours.tour_component_type',
            'flights.available_after')
        ->join('airlines', 'airline_id', 'airlines.id')
        ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
        ->join('travel_classes', 'flight_inventories.travel_class_id', 'travel_classes.id')
        ->join('flight_inventory_tours', 'flight_inventory_tours.flight_inventory_id', 'flight_inventories.id')
        ->whereNull('flight_inventory_tours.deleted_at')
        ->whereNull('flight_inventories.deleted_at')
        ->whereNull('flights.deleted_at')
        ->where('flight_inventory_tours.tour_id', $tour_id)
        ->where('flight_inventory_tours.tour_component_type', $tour_component_type)
        ->where(function($q) {
            $q->whereNull('flights.available_after')
                ->orWhere('flights.available_after', '<', date('Y-m-d'));
        });
        if (isset($flight_type) && strlen($flight_type)) {
            $flights = $flights->where('flight_inventory_tours.flight_type', $flight_type);
        } else {
            $flights = $flights->whereIn('flight_inventory_tours.flight_type', ['Outbound', 'Inbound'])
                ->orderBy('flight_inventory_tours.flight_type', 'desc');
        }
        $flightData = $flights
            ->orderBy('airlines.name', 'asc')
            ->get();

        $this->logging && Log::info("\n".'flightsAvaiableForTour:: flights  after:'.date('Y-m-d'). ' type:' . $flight_type .' tour_id:'.  $tour_id . ' : Recs : '. $flightData->count());
        
        return $flightData;
    }

    /**
     * flightsDepartingAfterToday
     *
     * @return void
     */
    public function flightsDepartingAfterToday(Airport $airport)
    {
        $today = date('Y-m-d');
        if (empty($airport)) {
            Log::error('flightsDepartingAfterToday needs an airport');
            throw new Exception('flightsDepartingAfterToday has no airport');
        }
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
    }
}
