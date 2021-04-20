<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccommodationInventory;
use App\Models\TourComponentType;
use App\Models\ActivityInventory;
use App\Models\FlightInventory;
use App\Models\TransportInventory;

class TourController extends Controller
{
    public function tourComponents($id)
    {

        dd(AccommodationInventory::findByTour($id));

        return view('tourComponents', [
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
