<?php

namespace App\Repository;

use App\Models\OrdersActivity;
use App\Models\OrdersCustomer;
use App\Models\Tour;

interface ActivityComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
    public static function getAvailableAddons($tourId, $oCustomerId = -1);
}

class ActivityComponentRepository implements ActivityComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersActivity::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersActivity::findOrFail($orderComponentId);
        return $orderComponent->activityInventoryTour()->first()->activityInventory()->first()->activity();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersActivity::findOrFail($orderComponentId);
        return $orderComponent->activityInventoryTour()->first()->activityInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId = -1) {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrdersCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->activityInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->activityInventory->activity->title;
                $components[$component->id]['activity_type'] = $component->activityInventory->activity->activityType->name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderActivities as $oComponent) {
                $component = $oComponent->activityInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $activityInventoryTourId)
    {
        return OrdersActivity::create([
            'order_customer_id' => $oCustomerId,
            'activity_inventory_tour_id' => $activityInventoryTourId,
        ]);
    }
}
