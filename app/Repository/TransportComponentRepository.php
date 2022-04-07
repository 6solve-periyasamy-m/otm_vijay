<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;
use Carbon\Carbon;

interface TransportComponentRepositoryInterface
{
}

class TransportComponentRepository implements TransportComponentRepositoryInterface
{
    public static function getAvailableAddons($tourId, $oCustomerId)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->transportInventoryTours as $component) {
            if ($component->tour_component_type  !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->transportInventory->transport->name;
                $components[$component->id]['transport_type'] = $component->transportInventory->transport->transportType->name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderTransports as $oComponent) {
                $component = $oComponent->transportInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $transportInventoryTourId)
    {
        $orderComponent = OrderTransport::create([
            'order_customer_id' => $oCustomerId,
            'transport_inventory_tour_id' => $transportInventoryTourId,
            'cost' => TransportInventoryTour::findOrFail($transportInventoryTourId)->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent;
    }

    public static function getParentComponent(TransportInventoryTour $inventoryTour)
    {
        $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        if (!isset($upgrade)) return $inventoryTour;
        return $upgrade->base;
    }

    public static function isOnUpgradeTree(TransportInventoryTour $inventoryTour, TransportInventoryTourUpgrade $upgrade): bool
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
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return TransportInventory::whereBetween('departs_at', [$dateFrom, $dateTo])->whereBetween('arrives_at', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }
}
