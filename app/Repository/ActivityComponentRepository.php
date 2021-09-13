<?php

namespace App\Repository;

use App\Models\OrdersActivity;

interface ActivityComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
}

class ActivityComponentRepository implements ActivityComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersActivity::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersActivity::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersActivity::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory();
    }
}