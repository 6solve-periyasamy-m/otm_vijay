<?php

namespace App\Transforms;

use App\Models\AccommodationInventory;
use App\Models\BoardType;
use App\Models\RoomType;

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
            $subData['text'] = $inventory->accommodation->name . " - " . $inventory->roomType->name;
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
        $data['text'] = $inventory->accommodation->name . " - " . $inventory->roomType->name;
        return $data;
    }
}
