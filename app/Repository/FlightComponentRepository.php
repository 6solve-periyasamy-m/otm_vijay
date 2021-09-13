<?php

namespace App\Repository;

use App\Models\OrdersFlight;

interface FlightComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
}

class FlightComponentRepository implements FlightComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersFlight::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersFlight::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersFlight::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory();
    }
}