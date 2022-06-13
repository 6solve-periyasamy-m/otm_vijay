<?php

namespace App\Repository;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;

class TourRepository
{

    public static function getTourDetails($id)
    {
        $tour = Tour::findOrFail($id);
        $details = ['tour' => $tour, ];
        $accommodationArr = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $data = [];
            $data['tour'] = $inventoryTour;
            $data['inventory'] = $inventoryTour->accommodationInventory;
            $data['component'] = $inventoryTour->accommodationInventory->accommodation;
            $accommodationArr[] = $data;
        }
        $details['accommodation'] = $accommodationArr;

        $activities = [];
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            $data = [];
            $data['tour'] = $inventoryTour;
            $data['inventory'] = $inventoryTour->activityInventory;
            $data['component'] = $inventoryTour->activityInventory->activity;
            $activities[] = $data;
        }
        $details['activities'] = $activities;

        $flights = [];
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            $data = [];
            $data['tour'] = $inventoryTour;
            $data['inventory'] = $inventoryTour->flightInventory;
            $data['component'] = $inventoryTour->flightInventory->flight;
            $flights[] = $data;
        }
        $details['flights'] = $flights;

        $transports = [];
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            $data = [];
            $data['tour'] = $inventoryTour;
            $data['inventory'] = $inventoryTour->transportInventory;
            $data['component'] = $inventoryTour->transportInventory->transport;
            $transports[] = $data;
        }
        $details['transports'] = $transports;

        return $details;
    }

    public static function getUpgradeIdFromAccommodation(AccommodationInventoryTour $inventoryTour): int
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public static function getUpgradeIdFromActivity(ActivityInventoryTour $inventoryTour): int
    {
        $upgrade = ActivityInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public static function getUpgradeIdFromFlight(FlightInventoryTour $inventoryTour): int
    {
        $upgrade = FlightInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = FlightInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public static function getUpgradeIdFromTransport(TransportInventoryTour $inventoryTour): int
    {
        $upgrade = TransportInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }
}
