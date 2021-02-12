<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //

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
                "check_in_date_time" => $flightInventory->check_in_date_time,
                "departure_date_time" => $flightInventory->departure_date_time,
                "class" => $flightInventory->travelClass->title,
                "airline" => $flightInventory->flight->airline->airline_name,
                "departure_airport" => $flightInventory->flight->departureAirport->airport_name,
                "arrival_airport" => $flightInventory->flight->arrivalAirport->airport_name,
            ];
        })->toArray();
        $result["success"] = true;
        return response()->json($result);
    }
}
