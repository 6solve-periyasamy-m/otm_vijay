<?php

namespace App\Repository;

use App\Models\AccommodationInventoryTour;
use App\Models\ActivityInventoryTour;
use App\Models\FlightInventoryTour;
use App\Models\Order;
use App\Models\OrderCustomer;
use App\Models\Tour;
use App\Models\TransportInventoryTour;
use Illuminate\Support\Facades\DB;

interface TourRepositoryInterface {
    public static function getTourDetails($id);

}

class TourRepository implements TourRepositoryInterface
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

    public static function getAvailableAccommodationForUpgrades(AccommodationInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->accommodationInventoryTours as $inventory) {
            $included[$inventory->id] = $inventory->id;
        }
        $data = [];
        foreach ($tourInventory->accommodationInventory->accommodation->inventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->check_in->gte($tour->date_from) && $inventory->check_out->lte($tour->date_to)) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public static function getAvailableActivityForUpgrades(ActivityInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->activityInventoryTours as $inventory) {
            $included[$inventory->id] = $inventory->id;
        }
        $data = [];
        foreach ($tourInventory->activityInventory->activity->activityInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->starts_at->gte($tour->date_from) && $inventory->ends_at->lte($tour->date_to)) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public static function getAvailableFlightForUpgrades(FlightInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->flightInventoryTours as $inventory) {
            $included[$inventory->id] = $inventory->id;
        }
        $data = [];
        foreach ($tourInventory->flightInventory->flight->flightInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->check_in->gte($tour->date_from) && $inventory->arrives_at->lte($tour->date_to)) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public static function getAvailableTransportForUpgrades(TransportInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->transportInventoryTours as $inventory) {
            $included[$inventory->id] = $inventory->id;
        }
        $data = [];
        foreach ($tourInventory->transportInventory->transport->transportInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->departs_at->gte($tour->date_from) && $inventory->arrives_at->lte($tour->date_to)) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }
}
