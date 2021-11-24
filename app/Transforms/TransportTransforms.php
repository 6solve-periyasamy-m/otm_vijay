<?php

namespace App\Transforms;

use App\Models\Operator;
use App\Models\TransportInventory;
use App\Models\TransportType;
use App\Models\TravelClass;

interface TransportTransformsInterface {
    public static function getSelectTransportTypes($filter);
    public static function getSelectOperators($filter);
    public static function getSelectTravelClasses($filter);
    public static function getSelectedTransportType($id);
    public static function getSelectedOperator($id);
    public static function getSelectedTravelClass($id);
    public static function getSelectInventory($filter);
    public static function getSelectedInventory($filter);
}

class TransportTransforms implements TransportTransformsInterface
{

    public static function getSelectTransportTypes($filter)
    {
        $data = [];
        foreach (TransportType::all() as $transportType) {
            $subData = [];
            $subData['id'] = $transportType->id;
            $subData['text'] = $transportType->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectOperators($filter)
    {
        $data = [];
        foreach (Operator::all() as $operator) {
            $subData = [];
            $subData['id'] = $operator->id;
            $subData['text'] = $operator->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectTravelClasses($filter) {
        $data = [];
        foreach (TravelClass::all() as $travelClass) {
            $subData = [];
            $subData['id'] = $travelClass->id;
            $subData['text'] = $travelClass->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedTransportType($id)
    {
        if ($id == 0) return null;
        $transportType = TransportType::findOrFail($id);
        $data = [];
        $data['id'] = $transportType->id;
        $data['text'] = $transportType->name;
        return $data;
    }

    public static function getSelectedOperator($id)
    {
        if ($id == 0) return null;
        $operator = Operator::findOrFail($id);
        $data = [];
        $data['id'] = $operator->id;
        $data['text'] = $operator->name;
        return $data;
    }

    public static function getSelectedTravelClass($id)
    {
        if ($id == 0) return null;
        $travelClass = TravelClass::findOrFail($id);
        $data = [];
        $data['id'] = $travelClass->id;
        $data['text'] = $travelClass->name;
        return $data;
    }

    public static function getSelectInventory($filter)
    {
        $data = [];
        foreach (TransportInventory::all() as $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->transport->name . ' - ' . $inventory->departureAddress->name . ' to ' . $inventory->arrivalAddress->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventory($id)
    {
        if ($id == 0) return null;
        $inventory = TransportInventory::findOrFail($id);
        $data = [];
        $data['id'] = $inventory->id;
        $data['text'] = $inventory->transport->name . ' - ' . $inventory->departureAddress->name . ' to ' . $inventory->arrivalAddress->name;
        return $data;
    }
}
