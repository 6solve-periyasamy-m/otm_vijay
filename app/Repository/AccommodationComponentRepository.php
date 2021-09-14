<?php

namespace App\Repository;

use App\Models\OrdersAccommodation;

interface AccommodationComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
}

class AccommodationComponentRepository implements AccommodationComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersAccommodation::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory();
    }
}
