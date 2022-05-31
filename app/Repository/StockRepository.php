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
        $query = DB::table('order_transports');
        $query->join('transport_inventory_tours', 'order_transports.transport_inventory_tour_id', '=', 'transport_inventory_tours.id');
        $query->join('transport_inventories', 'transport_inventory_tours.transport_inventory_id', '=', 'transport_inventories.id');
        $query->join('order_customers', 'order_transports.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('transport_inventories.id', '=', $inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_transports.deleted_at');
        return $query->selectRaw("count(order_transports.id) as 'used_stock'")->first()->used_stock;
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
        $query = DB::table('order_merchandises');
        $query->join('merchandises', 'order_merchandises.merchandise_id', '=', 'merchandises.id');
        $query->join('order_customers', 'order_merchandises.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('merchandises.id', '=', $merchandise->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_merchandises.deleted_at');
        return $query->selectRaw("count(order_merchandises.id) as 'used_stock'")->first()->used_stock;
    }

}
