<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Carbon\Carbon;

interface FlightComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);
}

class FlightComponentRepository implements FlightComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrderFlight::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderFlight::findOrFail($orderComponentId);
        return $orderComponent->flightInventoryTour()->first()->flightInventory()->first()->flight();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderFlight::findOrFail($orderComponentId);
        return $orderComponent->flightInventoryTour()->first()->flightInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->flightInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->flightInventory->flight_number;
                $components[$component->id]['travel_class'] = $component->flightInventory->travelClass->name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderFlights as $oComponent) {
                $component = $oComponent->flightInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $flightInventoryTourId)
    {
        $orderComponent = OrderFlight::create([
            'order_customer_id' => $oCustomerId,
            'flight_inventory_tour_id' => $flightInventoryTourId,
            'cost' => FlightInventoryTour::findOrFail($flightInventoryTourId)->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent;
    }

    public static function getParentComponent(FlightInventoryTour $inventoryTour)
    {
        $upgrade = FlightInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        if (!isset($upgrade)) return $inventoryTour;
        return $upgrade->base;
    }

    public static function isOnUpgradeTree(FlightInventoryTour $inventoryTour, FlightInventoryTourUpgrade $upgrade): bool
    {
        if ($upgrade->base_id == $inventoryTour->id) return true;
        foreach ($inventoryTour->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $inventories = [];
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return FlightInventory::whereBetween('check_in', [$dateFrom, $dateTo])->whereBetween('arrives_at', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }
}
