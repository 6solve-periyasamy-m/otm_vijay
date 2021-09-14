<?php

namespace App\Repository;

use App\Models\OrdersTransport;

interface TransportComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
}

class TransportComponentRepository implements TransportComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
        return OrdersTransport::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory()->first()->transport();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory();
    }
}
