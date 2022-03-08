<?php

namespace App\Repository;

use App\Models\AccommodationInventoryTour;
use App\Models\AccommodationInventoryTourUpgrade;
use App\Models\ActivityInventoryTour;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\FlightInventoryTour;
use App\Models\FlightInventoryTourUpgrade;
use App\Models\Order;
use App\Models\OrderCustomer;
use App\Models\Tour;
use App\Models\TransportInventoryTour;
use App\Models\TransportInventoryTourUpgrade;
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
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $included[$inventoryTour->accommodationInventory->id] = $inventoryTour->accommodationInventory->id;
        }
        $data = [];
        foreach ($tourInventory->accommodationInventory->accommodation->inventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->check_in->gte($tour->date_from->setTime(0,0,0)) && $inventory->check_out->lte($tour->date_to->setTime(11, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public static function getAvailableActivityForUpgrades(ActivityInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            $included[$inventoryTour->activityInventory->id] = $inventoryTour->activityInventory->id;
        }
        $data = [];
        foreach ($tourInventory->activityInventory->activity->activityInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->starts_at->gte($tour->date_from->setTime(0,0,0)) && $inventory->ends_at->lte($tour->date_to->setTime(11, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public static function getAvailableFlightForUpgrades(FlightInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            $included[$inventoryTour->flightInventory->id] = $inventoryTour->flightInventory->id;
        }
        $data = [];
        foreach ($tourInventory->flightInventory->flight->flightInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->check_in->gte($tour->date_from->setTime(0,0,0)) && $inventory->arrives_at->lte($tour->date_to->setTime(11, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public static function getAvailableTransportForUpgrades(TransportInventoryTour $tourInventory)
    {
        $tour = $tourInventory->tour;
        $included = [];
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            $included[$inventoryTour->transportInventory->id] = $inventoryTour->transportInventory->id;
        }
        $data = [];
        foreach ($tourInventory->transportInventory->transport->transportInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->departs_at->gte($tour->date_from->setTime(0,0,0)) && $inventory->arrives_at->lte($tour->date_to->setTime(11, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
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

    public static function autoAssignTemplating(Tour $tour)
    {
        $dates = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $start = $inventoryTour->inventory->check_in->clone();
            $start->setTime(0,0,0);
            if (array_key_exists($start->unix(), $dates)) {
                if ($inventoryTour->is_template && !$dates[$start->unix()]->is_template) {
                    $dates[$start->unix()] = $inventoryTour;
                }
            } else {
                $dates[$start->unix()] = $inventoryTour;
            }
        }
        foreach ($dates as $inventoryTour) {
            $inventoryTour->is_template = true;
            $inventoryTour->save();
        }
    }

    public static function getTemplateData(Tour $tour): array
    {
        $data = [];
        foreach (AccommodationComponentRepository::getTemplateTourInventory($tour) as $template) {
            $templateData = ['template' => $template, 'available' => []];
            foreach (AccommodationComponentRepository::getHydratedRoomTypesForInventory($template) as $roomType) {
                $templateData['available'][] = $roomType;
            }
            $data[] = $templateData;
        }
        return $data;
    }

    public static function clone(Tour $oldTour): Tour
    {
        $newTour = $oldTour->replicate();
        $newTour->save();
        foreach ($oldTour->accommodationInventoryTours as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        foreach ($oldTour->activityInventoryTours as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        foreach ($oldTour->flightInventoryTours as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        foreach ($oldTour->transportInventoryTours as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        foreach ($oldTour->merchandise as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        foreach ($oldTour->paymentInstallments as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        return $newTour;
    }
}
