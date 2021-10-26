<?php

namespace App\Transforms;

use App\Models\HatSize;
use App\Models\TShirtSize;

interface CustomerTransformsInterface
{
    public static function getSelectTShirtSizes($filter);

    public static function getSelectedTShirtSize($id);

    public static function getSelectHatSizes($filter);

    public static function getSelectedHatSize($id);
}

class CustomerTransforms implements CustomerTransformsInterface
{

    public static function getSelectTShirtSizes($filter)
    {
        $data = [];
        foreach (TShirtSize::all() as $size) {
            $subData = [];
            $subData['id'] = $size->id;
            $subData['text'] = $size->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedTShirtSize($id)
    {
        if ($id == 0) return null;
        $size = TShirtSize::findOrFail($id);
        $data = [];
        $data['id'] = $size->id;
        $data['text'] = $size->name;
        return $data;
    }

    public static function getSelectHatSizes($filter)
    {
        $data = [];
        foreach (HatSize::all() as $size) {
            $subData = [];
            $subData['id'] = $size->id;
            $subData['text'] = $size->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedHatSize($id)
    {
        if ($id == 0) return null;
        $size = HatSize::findOrFail($id);
        $data = [];
        $data['id'] = $size->id;
        $data['text'] = $size->name;
        return $data;
    }
}
