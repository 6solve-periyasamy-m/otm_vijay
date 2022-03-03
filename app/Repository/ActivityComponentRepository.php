<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\ActivityInventory;
use App\Models\ActivityInventoryTour;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface ActivityComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getAvailableAddons($tourId, $oCustomerId = -1);

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);
}

class ActivityComponentRepository implements ActivityComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrderActivity::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderActivity::findOrFail($orderComponentId);
        return $orderComponent->activityInventoryTour()->first()->activityInventory()->first()->activity();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderActivity::findOrFail($orderComponentId);
        return $orderComponent->activityInventoryTour()->first()->activityInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId = -1)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->activityInventoryTours as $component) {
            if ($component->tour_component_type  !== "Upgrade") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->activityInventory->activity->name;
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
        $orderComponent = OrderActivity::create([
            'order_customer_id' => $oCustomerId,
            'activity_inventory_tour_id' => $activityInventoryTourId,
            'cost' => ActivityInventoryTour::findOrFail($activityInventoryTourId)->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent;
    }

    public static function getParentComponent(ActivityInventoryTour $inventoryTour) {
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        if (!isset($upgrade)) return $inventoryTour;
        return $upgrade->base;
    }

    public static function isOnUpgradeTree(ActivityInventoryTour $inventoryTour, ActivityInventoryTourUpgrade $upgrade): bool
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
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return ActivityInventory::whereBetween('starts_at', [$dateFrom, $dateTo])->whereBetween('ends_at', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }
}
