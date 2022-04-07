<?php

namespace App\Http\Controllers;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Event;
use App\Models\Flight\FlightInventory;
use App\Models\Tour;
use App\Models\TourComponentType;
use App\Models\TransportInventory;

class TourController extends Controller
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
    public function tourComponents($id)
    {

        dd(AccommodationInventory::findByTour($id));

        return view('pages.components.tour', [
            'accommodationInventories' => AccommodationInventory::findByTour($id),
            'componentTypes' => TourComponentType::all(),
            'activityInventories' => ActivityInventory::findByTour($id),
            'flightInventories' => FlightInventory::findByTour($id),
            'transportInventories' => TransportInventory::findByTour($id),

            ]);
    }

    public function tourComponentUpdate($id)
    {

    }
}
