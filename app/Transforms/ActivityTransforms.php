<?php

namespace App\Transforms;

use App\Models\ActivityInventory;
use App\Models\ActivityType;
use App\Models\TicketType;

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
            $subData['text'] = $inventory->activity->name . ' - ' . $inventory->activity->activityType->name;
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
        $data['text'] = $inventory->activity->name . ' - ' . $inventory->activity->activityType->name;
        return $data;
    }
}
