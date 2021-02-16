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

use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Open API - populate the booking form selectors
    public function getTours(Event $event_id = null) {
        $today = date('Y-m-d');
        $tours = Tour::whereNull('event_id')
            ->orWhere('event_id', $event_id)
            ->whereNull('date_from')
            ->orWhere(DB::raw("(STR_TO_DATE(tours.date_from,'%y-%m-%d'))"), ">=", $today)
            ->get();
        $data = $tours->toArray();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getAirlines() 
    {
        $airlines = Airline::orderBy('airline_name')->get();

        return response()->json(["success" => true, "data" => $airlines->toArray()]);
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
        // TODO: I'm assuming there is going to be some sort of authentication check here somewhere
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
