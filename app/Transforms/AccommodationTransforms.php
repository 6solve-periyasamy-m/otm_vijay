<?php

namespace App\Transforms;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\BoardType;
use App\Models\Order\OrderCustomer;
use App\Models\RoomType;
use App\Repository\Facades\StringFormatter;
use App\Repository\TourRepository;

interface AccommodationTransformsInterface {
    public static function getSelectRoomTypes($filter);
    public static function getSelectBoardTypes($filter);
    public static function getSelectedRoomType($id);
    public static function getSelectedBoardType($id);
    public static function getSelectInventory($filter);
    public static function getSelectedInventory($filter);
}

class AccommodationTransforms implements AccommodationTransformsInterface
{

    public static function getSelectRoomTypes($filter)
    {
        $data = [];
        foreach (RoomType::all() as $roomType) {
            $subData = [];
            $subData['id'] = $roomType->id;
            $subData['text'] = $roomType->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectBoardTypes($filter)
    {
        $data = [];
        foreach (BoardType::all() as $boardType) {
            $subData = [];
            $subData['id'] = $boardType->id;
            $subData['text'] = $boardType->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedRoomType($id)
    {
        if ($id == 0) return null;
        $roomType = RoomType::findOrFail($id);
        $data = [];
        $data['id'] = $roomType->id;
        $data['text'] = $roomType->name;
        return $data;
    }

    public static function getSelectedBoardType($id)
    {
        if ($id == 0) return null;
        $boardType = BoardType::findOrFail($id);
        $data = [];
        $data['id'] = $boardType->id;
        $data['text'] = $boardType->name;
        return $data;
    }

    public static function getSelectInventory($filter)
    {
        $data = [];
        foreach (AccommodationInventory::all() as $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->accommodation->name . " - " . $inventory->roomType->name . ' - ' . $inventory->boardType->name . ' - ' . $inventory->check_in . ' to ' . $inventory->check_out;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventory($id)
    {
        if ($id == 0) return null;
        $inventory = AccommodationInventory::findOrFail($id);
        $data = [];
        $data['id'] = $inventory->id;
        $data['text'] = $inventory->accommodation->name . " - " . $inventory->roomType->name . ' - ' . $inventory->boardType->name . ' - ' . $inventory->check_in . ' to ' . $inventory->check_out;
        return $data;
    }

    public static function getSelectInventoryForAccommodation(AccommodationInventoryTour $tourInventory, $filter) {
        $available = TourRepository::getAvailableAccommodationForUpgrades($tourInventory);
        $data = [];
        foreach ($available as $id => $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->roomType->name . ' - ' . $inventory->boardType->name . ' - ' . $inventory->check_in . ' to ' . $inventory->check_out;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventoryForAccommodation($id) {
        if ($id == 0) return null;
        $inventory = AccommodationInventory::findOrFail($id);
        $subData = [];
        $subData['id'] = $inventory->id;
        $subData['text'] = $inventory->roomType->name . ' - ' . $inventory->boardType->name . ' - ' . $inventory->check_in . ' to ' . $inventory->check_out;
        return $subData;
    }

    public static function getAvailableAddons(OrderCustomer $orderCustomer, string $filter)
    {
        $group = $orderCustomer->primary_group;
        $tour = $orderCustomer->order->tour;
        $data = [];
        $owned = [];
        if (!isset($group)) return $data;
        foreach ($group->rooms as $orderComponent) $owned[] = $orderComponent->tourComponent->id;
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== "Upgrade") {
                if (in_array($inventoryTour->id, $owned)) continue;
                $subData = [];
                $subData['id'] = $inventoryTour->id;
                $subData['text'] = $inventoryTour . " ({$inventoryTour->tour_component_type})"
                    . ' - ' .
                    ($inventoryTour->tour_component_type === 'Included' ? 'Included with Basic Package' :
                        StringFormatter::formatCurrency($inventoryTour->tour_sales_price));
                if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
            }
        }
        return $data;

    }
}
