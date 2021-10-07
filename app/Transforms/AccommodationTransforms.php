<?php

namespace App\Transforms;

use App\Models\BoardType;
use App\Models\RoomType;

interface AccommodationTransformsInterface {
    public static function getSelectRoomTypes();
    public static function getSelectBoardTypes();
    public static function getSelectedRoomType($id);
    public static function getSelectedBoardType($id);
}

class AccommodationTransforms implements AccommodationTransformsInterface
{

    public static function getSelectRoomTypes()
    {
        $data = [];
        foreach (RoomType::all() as $roomType) {
            $subData = [];
            $subData['id'] = $roomType->id;
            $subData['text'] = $roomType->room_type_name;
            $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectBoardTypes()
    {
        $data = [];
        foreach (BoardType::all() as $boardType) {
            $subData = [];
            $subData['id'] = $boardType->id;
            $subData['text'] = $boardType->board_type_name;
            $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedRoomType($id)
    {
        if ($id == 0) return null;
        $roomType = RoomType::findOrFail($id);
        $data = [];
        $data['id'] = $roomType->id;
        $data['text'] = $roomType->room_type_name;
        return $data;
    }

    public static function getSelectedBoardType($id)
    {
        if ($id == 0) return null;
        $boardType = BoardType::findOrFail($id);
        $data = [];
        $data['id'] = $boardType->id;
        $data['text'] = $boardType->board_type_name;
        return $data;
    }
}
