<?php

namespace App\Repository;

use App\Models\OrdersActivity;
use App\Models\OrdersCustomer;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface ActivityComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getAvailableAddons($tourId, $oCustomerId = -1);

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);
}

class ActivityComponentRepository implements ActivityComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
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

    public static function getAvailableAddons($tourId, $oCustomerId = -1)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrdersCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->activityInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
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
        return OrdersActivity::create([
            'order_customer_id' => $oCustomerId,
            'activity_inventory_tour_id' => $activityInventoryTourId,
        ]);
    }

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $alreadyAdded = [];
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            $alreadyAdded[$inventoryTour->activityInventory->id] = $inventoryTour->activityInventory->id;
        }
        $query = DB::table('activity_inventories');
        $query->join('activities', 'activity_inventories.activity_id', '=', 'activities.id');
        $query->join('activity_types', 'activities.activity_type_id', '=', 'activity_types.id');
        $query->join('ticket_types', 'activity_inventories.ticket_type_id', '=', 'ticket_types.id');
        $query->join('locations', 'activities.location_id', '=', 'locations.id');
        $query->select(
            'activity_inventories.id AS id',
            'activities.id AS activity_id',
            'activities.title AS name',
            'activities.description AS description',
            'locations.name AS location',
            'ticket_types.name AS ticket_type',
            'activity_types.name AS activity_type',
            'activity_inventories.activity_start_date_time AS start_date',
            'activity_inventories.activity_end_date_time AS end_date',
            DB::raw('CASE WHEN `activity_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END AS fit_selectable'),
            'activity_inventories.stock AS stock',
            'activity_inventories.purchase_price AS purchase_price',
            'activity_inventories.sales_price AS sales_price',
            'activity_inventories.notes AS notes'
        );
        $query->whereNotIn('activity_inventories.id', $alreadyAdded);
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `activity_inventories`.`activity_start_date_time` AND `activity_inventories`.`activity_end_date_time`");
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `activity_inventories`.`activity_start_date_time` AND `activity_inventories`.`activity_end_date_time`");
        return $query->get();
    }
}
