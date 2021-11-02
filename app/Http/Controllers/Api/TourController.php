<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Log;

use App\Models\Event;
use App\Models\Tour;
use App\Models\OrderCustomer;

class TourController extends ApiController
{
    public function getEvents() {
        $events = Event::where('starts_at', '>', date('Y-m-d'))->get();

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

    // Autheticated API - return data for logged in user sessions
    public function getBasicTourInformation(Tour $tour)
    {
        $tour = Tour::findOrFail($tour->id);

        return response()->json([
            "success" => true,
            "title" => $tour->name,
            "description" => $tour->description,
            "base_price_per_person" => $tour->base_price_per_person,
            // tour_colour - is an ID so i'm assuming there would be a relationship, doesn't exist yet
            // tour_merchandise - is an ID so i'm assuming there would be a relationship, doesn't exist yet


            // This is just a basic start with the models that I have access to and the relationships I currently have
        ]);
    }
}
