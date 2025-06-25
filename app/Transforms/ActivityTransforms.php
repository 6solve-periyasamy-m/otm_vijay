<?php

namespace App\Transforms;

use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityType;
use App\Models\Activity\TicketType;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Event;

interface ActivityTransformsInterface {
    public static function getSelectActivityTypes($filter);
    public static function getSelectTicketTypes($filter);
    public static function getSelectedActivityType($id);
    public static function getSelectedTicketType($id);
    public static function getSelectInventory($filter);
    public static function getSelectedInventory($filter);
}

class ActivityTransforms implements ActivityTransformsInterface
{
    public static function getSelectActivityTypes($filter)
    {
        $data = [];
        foreach (ActivityType::all() as $activityType) {
            $subData = [];
            $subData['id'] = $activityType->id;
            $subData['text'] = $activityType->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectTicketTypes($filter)
    {
        $data = [];
        foreach (TicketType::all() as $ticketType) {
            $subData = [];
            $subData['id'] = $ticketType->id;
            $subData['text'] = $ticketType->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedActivityType($id)
    {
        if ($id == 0) return null;
        $activityType = ActivityType::findOrFail($id);
        $data = [];
        $data['id'] = $activityType->id;
        $data['text'] = $activityType->name;
        return $data;
    }

    public static function getSelectedTicketType($id)
    {
        if ($id == 0) return null;
        $ticketType = TicketType::findOrFail($id);
        $data = [];
        $data['id'] = $ticketType->id;
        $data['text'] = $ticketType->name;
        return $data;
    }

    public static function getSelectInventory($filter)
    {
        $data = [];
        foreach (ActivityInventory::all() as $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->activity->name . ' - ' . $inventory->activity->activityType->name . ' - ' . $inventory->ticketType->name . ' - ' . $inventory->starts_at . ' to ' . $inventory->ends_at;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventory($id)
    {
        if ($id == 0) return null;
        $inventory = ActivityInventory::findOrFail($id);
        $data = [];
        $data['id'] = $inventory->id;
        $data['text'] = $inventory->activity->name . ' - ' . $inventory->activity->activityType->name . ' - ' . $inventory->ticketType->name . ' - ' . $inventory->check_in . ' to ' . $inventory->check_out;
        return $data;
    }

    public static function getSelectInventoryForActivity(ActivityInventoryTour $tourInventory, $filter) {
        $available = $tourInventory->repository->getAvailableForUpgrade();
        $data = [];
        foreach ($available as $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->activity->name . ' - ' . $inventory->ticketType->name . ' - ' . $inventory->starts_at . ' to ' . $inventory->ends_at;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventoryForActivity($id) {
        if ($id == 0) return null;
        $inventory = ActivityInventory::findOrFail($id);
        $subData = [];
        $subData['id'] = $inventory->id;
        $subData['text'] = $inventory->ticketType->name . ' - ' . $inventory->starts_at . ' to ' . $inventory->ends_at;
        return $subData;
    }

    public static function getAvailableAddons(OrderCustomer $orderCustomer, string|null $filter)
    {
        $tour = $orderCustomer->order->tour;
        $data = [];
        $owned = [];
        foreach ($orderCustomer->orderActivities as $orderActivity) $owned[] = $orderActivity->tourComponent->id;
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type  !== "Upgrade") {
                if (in_array($inventoryTour->id, $owned)) continue;
                $subData = [];
                $subData['id'] = $inventoryTour->id;
                $subData['text'] = $inventoryTour . " ({$inventoryTour->tour_component_type})"
                    . ' - ' .
                    ($inventoryTour->tour_component_type === 'Included' ? 'Included with Basic Package' :
                        f_currency($inventoryTour->tour_sales_price));
                if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
            }
        }
        return $data;
    }
}
