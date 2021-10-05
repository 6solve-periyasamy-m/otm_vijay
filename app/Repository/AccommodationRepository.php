<?php

namespace App\Repository;

use App\Models\BoardType;
use App\Models\RoomType;

interface AccommodationRepositoryInterface {
    public static function getSelectRoomTypes($filter);
    public static function getSelectBoardTypes($filter);
    public static function getSelectedRoomType($id);
    public static function getSelectedBoardType($id);
}

class AccommodationRepository implements AccommodationRepositoryInterface
{

    public static function getSelectRoomTypes($filter)
    {
        $data = [];
        foreach (RoomType::all() as $roomType) {
            $subData = [];
            $subData['id'] = $roomType->id;
            $subData['text'] = $roomType->room_type_name;
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
            $subData['text'] = $boardType->board_type_name;
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
