<?php

namespace App\Repository;

use App\Models\OrdersAccommodation;
use App\Models\OrdersCustomer;
use App\Models\Tour;

interface AccommodationComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
    public static function getAvailableAddons($tourId, $oCustomerId = -1);
    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);
}

class AccommodationComponentRepository implements AccommodationComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersAccommodation::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId = -1) {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrdersCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->accommodationInventory->accommodation->title;
                $components[$component->id]['room_type'] = $component->accommodationInventory->roomType->room_type_name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderAccommodation as $oComponent) {
                $component = $oComponent->accommodationInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId) {
        return OrdersAccommodation::create([
            'order_customer_id' => $oCustomerId,
            'accommodation_inventory_tour_id' => $accommodationInventoryTourId,
            'share_with_user_id' => null
        ]);
    }
}
