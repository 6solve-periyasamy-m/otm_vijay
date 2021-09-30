<?php

namespace App\Repository;

use App\Models\OrdersCustomer;
use App\Models\OrdersTransport;
use App\Models\Tour;

interface TransportComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
}

class TransportComponentRepository implements TransportComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersTransport::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory()->first()->transport();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrdersCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->transportInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
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
        return OrdersTransport::create([
            'order_customer_id' => $oCustomerId,
            'transport_inventory_tour_id' => $transportInventoryTourId,
        ]);
    }
}
