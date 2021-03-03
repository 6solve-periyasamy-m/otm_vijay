<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\Tour;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\PaymentSchedule;
use App\Models\PaymentInstallment;
use App\Repository\FlightsRepository;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Open API - populate the booking form selectors
    public function getEvents() {
        $events = Event::where('event_start_date', '>', date('Y-m-d'))->get();

        return response()->json(['success' => true, 'data' => $events->toArray()]);
    }

    public function getTours($event_id = null) {
        $today = date('Y-m-d');
        if ($event_id) {
            $tours = Tour::where('event_id', $event_id)
                        ->get();
        } else {
            $tours = Tour::get();
        }

        return response()->json(['success' => true, 'data' => $tours->toArray()]);
    }

    public function getAirlines() 
    {
        $airlines = Airline::orderBy('airline_name')->get();

        return response()->json(["success" => true, "data" => $airlines->toArray()]);
    }


    public function getAirports($location_id = null, $region_id = null) 
    {
        $airport = new Airport();
        $airport = $airport->select('airports.*')
                        ->join('locations', 'location_id', 'locations.id')
                        ->join('regions', 'locations.region_id', 'regions.id');
        if (isset($location_id)) {
            $airport = $airport->where('location_id', $location_id);
        }
        if (isset($region_id)) {
            $airport = $airport->where('region_id', $region_id);
        }

        $airports = $airport->orderBy('airport_name', 'asc')->get()->toArray();
        $airports = array_combine(array_column($airports,'id'),$airports);

        return response()->json(["success" => true, "airports" => $airports]);
    }

    public function getFlightInventories()
    {
        $flights = Flight::join('airlines', 'airline_id', 'airlines.id')
            ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
            ->get();

        return response()->json(["success" => true, "data" => $flights->toArray()]);
    }

    /***
     * flights booked for a tour create records in the flight_inventory_tours table
     * these associate a flight_inventory_id with a tour_id (so the tour booking creates these)
     */
    public function getFlightInventoriesForTour($tour_id, $flight_type = null)
    {
        $flight = new Flight();
        // $flightsRepository = new FlightsRepository($flight);
        // $flights = $flightsRepository->flights($tour_id);

        $flights = Flight::select('flight_inventories.*', 'flights.departure_airport_id', 'flights.arrival_airport_id', 'airlines.airline_name', 'travel_classes.title as travel_class')
            ->join('airlines', 'airline_id', 'airlines.id')
            ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
            ->join('travel_classes', 'flight_inventories.travel_class_id', 'travel_classes.id')
            ->join('flight_inventory_tour', 'flight_inventory_tour.flight_inventory_id', 'flight_inventories.id')
            ->where('flight_inventory_tour.tour_id', $tour_id);
        if (isset($flight_type)) {
            $flights = $flights->where('flight_type', $flight_type);
        } else {
            $flights = $flights->whereIn('flight_type', ['Outbound', 'Inbound'])
                ->orderBy('flight_type', 'desc');
        }
        $flights = $flights 
            ->orderBy('airlines.airline_name', 'asc')
            ->get();

        // \Log::info('flights', $flights->toArray());
        return response()->json(["success" => true, "data" => $flights->toArray()]);

    }

    public function getPaymentSchedules()
    {
        $schedules = PaymentSchedule::orderBy('title')->get();

        return response()->json(["success" => true, "schedules" => $schedules->toArray()]);
    }

    public function getPaymentSchedule($id) 
    {
        $schedule = PaymentSchedule::findOrFail($id);
\Log::debug('schedule for id '.$id, $schedule->toArray());
        return response()->json(["success" => true, "schedule" => $schedule->toArray()]);
    }

    public function getFlightsFromAirport(Airport $airport = null)
    {
        // Returns a list of flights from an airport
        $today = date('Y-m-d');
        $flights = Flight::where('departure_airport_id', $airport->id)
            ->orWhere(function($query) {
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

    // Autheticated API - return data for logged in user sessions
    public function getBasicTourInformation(Tour $tour)
    {
        $tour = Tour::findOrFail($tour->id);

        return response()->json([
            "success" => true,
            "title" => $tour->title,
            "description" => $tour->description,
            "base_price_per_person" => $tour->base_price_per_person,
            // tour_colour - is an ID so i'm assuming there would be a relationship, doesn't exist yet
            // tour_merchandise - is an ID so i'm assuming there would be a relationship, doesn't exist yet


            // This is just a basic start with the models that I have access to and the relationships I currently have
        ]);
    }

    public function getFlightsFromTour(Tour $tour)
    {
        // TODO: As above, add authentication.
        // You don't want scrapers just scraping all of the information out of the DB from these APIs
        $inventory = $tour->flightInventory;
        $result = $inventory->map(function ($flightInventory) {
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

    public function getAccommodationFromTour(Tour $tour) // would use route model binding
    {
        $inventory = $tour->accommodationInventory;
        $result = $inventory->map(function ($accommodationInventory) {
            return [
                "id" => $accommodationInventory->id,
                "accommodation_id" => $accommodationInventory->accommodation->id,
                "check_in_date_time" => $accommodationInventory->check_in_date_time->format('Y-m-d H:i:s'),
                "check_out_date_time" => $accommodationInventory->check_out_date_time->format('Y-m-d H:i:s'),
                "accommodation_name" => $accommodationInventory->accommodation->title,
                "accommodation_address" => $accommodationInventory->accommodation->address,
                "room_type" => $accommodationInventory->roomType->room_type_name,
                "board_type" => $accommodationInventory->boardType->board_type_name,
            ];
        })->toArray();
        return response()->json(["success" => true, "data" => $result]);
    }
}
