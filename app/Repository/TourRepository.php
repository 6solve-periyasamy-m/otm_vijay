<?php

namespace App\Repository;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\ActivityInventoryTour;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\FlightInventoryTour;
use App\Models\FlightInventoryTourUpgrade;
use App\Models\Tour;
use App\Models\TransportInventoryTour;
use App\Models\TransportInventoryTourUpgrade;

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
            if ($inventory->check_in->gte($tour->date_from->setTime(0,0,0)) && $inventory->check_out->lte($tour->date_to->setTime(23, 59, 59))) {
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
            if ($inventory->starts_at->gte($tour->date_from->setTime(0,0,0)) && $inventory->ends_at->lte($tour->date_to->setTime(23, 59, 59))) {
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
            if ($inventory->check_in->gte($tour->date_from->setTime(0,0,0)) && $inventory->arrives_at->lte($tour->date_to->setTime(23, 59, 59))) {
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
            if ($inventory->departs_at->gte($tour->date_from->setTime(0,0,0)) && $inventory->arrives_at->lte($tour->date_to->setTime(23, 59, 59))) {
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
            if ($inventoryTour->tour_component_type == 'Upgrade') continue;
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
            foreach ($inventoryTour->upgrades as $upgrade) {
                $newUpgrade = $upgrade->replicate();
                $newUpgrade->base_id = $newInventoryTour->id;
                $clonedInventoryUpgrade = $upgrade->upgrade->replicate();
                $clonedInventoryUpgrade->tour_id = $newTour->id;
                $clonedInventoryUpgrade->save();
                $newUpgrade->upgrade_id = $clonedInventoryUpgrade->id;
                $newUpgrade->save();
            }
        }
        foreach ($oldTour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Upgrade') continue;
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
            foreach ($inventoryTour->upgrades as $upgrade) {
                $newUpgrade = $upgrade->replicate();
                $newUpgrade->base_id = $newInventoryTour->id;
                $clonedInventoryUpgrade = $upgrade->upgrade->replicate();
                $clonedInventoryUpgrade->tour_id = $newTour->id;
                $clonedInventoryUpgrade->save();
                $newUpgrade->upgrade_id = $clonedInventoryUpgrade->id;
                $newUpgrade->save();
            }
        }
        foreach ($oldTour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Upgrade') continue;
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
            foreach ($inventoryTour->upgrades as $upgrade) {
                $newUpgrade = $upgrade->replicate();
                $newUpgrade->base_id = $newInventoryTour->id;
                $clonedInventoryUpgrade = $upgrade->upgrade->replicate();
                $clonedInventoryUpgrade->tour_id = $newTour->id;
                $clonedInventoryUpgrade->save();
                $newUpgrade->upgrade_id = $clonedInventoryUpgrade->id;
                $newUpgrade->save();
            }
        }
        foreach ($oldTour->transportInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Upgrade') continue;
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
            foreach ($inventoryTour->upgrades as $upgrade) {
                $newUpgrade = $upgrade->replicate();
                $newUpgrade->base_id = $newInventoryTour->id;
                $clonedInventoryUpgrade = $upgrade->upgrade->replicate();
                $clonedInventoryUpgrade->tour_id = $newTour->id;
                $clonedInventoryUpgrade->save();
                $newUpgrade->upgrade_id = $clonedInventoryUpgrade->id;
                $newUpgrade->save();
            }
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

    public static function fixUpgrades(Tour $tour) {
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = AccommodationInventoryTour::join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id',  '=', 'accommodation_inventories.id')
                ->where('accommodation_inventory_tours.tour_id', '=', $tour->id)
                ->where('accommodation_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('accommodation_inventories.accommodation_id', '=', $inventoryTour->inventory->component->id)
                ->where('accommodation_inventories.check_in', '=', $inventoryTour->inventory->check_in)
                ->where('accommodation_inventories.room_type_id', '=', $inventoryTour->inventory->room_type_id)
                ->select('*', 'accommodation_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = AccommodationInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = AccommodationInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->boardType,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = ActivityInventoryTour::join('activity_inventories', 'activity_inventory_tours.activity_inventory_id',  '=', 'activity_inventories.id')
                ->where('activity_inventory_tours.tour_id', '=', $tour->id)
                ->where('activity_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('activity_inventories.activity_id', '=', $inventoryTour->inventory->component->id)
                ->where('activity_inventories.starts_at', '=', $inventoryTour->inventory->starts_at)
                ->select('*', 'activity_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = ActivityInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = ActivityInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->ticketType,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = FlightInventoryTour::join('flight_inventories', 'flight_inventory_tours.flight_inventory_id',  '=', 'flight_inventories.id')
                ->where('flight_inventory_tours.tour_id', '=', $tour->id)
                ->where('flight_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('flight_inventories.flight_id', '=', $inventoryTour->inventory->component->id)
                ->where('flight_inventories.departs_at', '=', $inventoryTour->inventory->check_in)
                ->select('*', 'flight_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = FlightInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = FlightInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->travelClass,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = TransportInventoryTour::join('transport_inventories', 'transport_inventory_tours.transport_inventory_id',  '=', 'transport_inventories.id')
                ->where('transport_inventory_tours.tour_id', '=', $tour->id)
                ->where('transport_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('transport_inventories.transport_id', '=', $inventoryTour->inventory->component->id)
                ->where('transport_inventories.departs_at', '=', $inventoryTour->inventory->departs_at)
                ->select('*', 'transport_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = TransportInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = TransportInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->travelClass,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
    }
}
