<?php

namespace App\Repository;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Flight\FlightInventory;
use App\Models\Tour\Merchandise;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use DB;

class StockRepository
{
    // Possible Optimization: SQL count query rather than O(n^2) nested for loops

    /**
     * Get the amount of used stock for an AccommodationInventory
     * @param AccommodationInventory $inventory The inventory to check
     * @returns int The amount of stock that has been used
     */
    public static function getAccommodationStock(AccommodationInventory $inventory): int
    {
        $used = 0;
        foreach ($inventory->tourComponents as $component) {
            foreach ($component->orders as $orderComponent) {
                if (!$orderComponent->cancelled) $used++;
            }
        }
        return $used;
    }

    /**
     * Get the amount of used stock for an ActivityInventory
     * @param ActivityInventory $inventory The inventory to check
     * @return int The amount of stock that has been used
     */
    public static function getActivityStock(ActivityInventory $inventory): int
    {
        $query = DB::table('order_activities');
        $query->join('activity_inventory_tours', 'order_activities.activity_inventory_tour_id', '=', 'activity_inventory_tours.id');
        $query->join('activity_inventories', 'activity_inventory_tours.activity_inventory_id', '=', 'activity_inventories.id');
        $query->join('order_customers', 'order_activities.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('activity_inventories.id', '=', $inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_activities.deleted_at');
        return $query->selectRaw("count(order_activities.id) as 'used_stock'")->first()->used_stock;
    }

    /**
     * Get the amount of used stock for a FlightInventory
     * @param FlightInventory $inventory The inventory to check
     * @return int The amount of stock that has been used
     */
    public static function getFlightStock(FlightInventory $inventory): int
    {
        $query = DB::table('order_flights');
        $query->join('flight_inventory_tours', 'order_flights.flight_inventory_tour_id', '=', 'flight_inventory_tours.id');
        $query->join('flight_inventories', 'flight_inventory_tours.flight_inventory_id', '=', 'flight_inventories.id');
        $query->join('order_customers', 'order_flights.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('flight_inventories.id', '=', $inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_flights.deleted_at');
        return $query->selectRaw("count(order_flights.id) as 'used_stock'")->first()->used_stock;
    }

    /**
     * Get the amount of used stock for a TransportInventory
     * @param TransportInventory $inventory The inventory to check
     * @return int The amount of stock that has been used
     */
    public static function getTransportStock(TransportInventory $inventory): int
    {
        $used = 0;
        foreach ($inventory->tourComponents as $component) {
            foreach ($component->orders as $orderComponent) {
                if (!$orderComponent->cancelled) $used++;
            }
        }
        return $used;
    }

    /**
     * Get the amount of used stock for a Tour
     * @param Tour $tour The tour to check
     * @return int The amount of stock that has been used
     */
    public static function getTourStock(Tour $tour): int
    {
        $used = 0;
        foreach ($tour->orders as $order) {
            if (!$order->cancelled) $used += $order->orderCustomers()->count();
        }
        return $used;
    }

    public static function getExtraStock(Merchandise $merchandise): int
    {
        $used = 0;
        foreach ($merchandise->orderMerchandise as $orderMerchandise) {
            if (!$orderMerchandise->cancelled) $used++;
        }
        return $used;
    }

}
