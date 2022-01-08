<?php

namespace App\Repository;

use App\Models\AccommodationInventory;
use App\Models\ActivityInventory;
use App\Models\FlightInventory;
use App\Models\Tour;
use App\Models\TransportInventory;

class StockRepository
{
    // TODO: Implement checking for order cancellation

    /**
     * Get the amount of used stock for an AccommodationInventory
     * @param AccommodationInventory $inventory The inventory to check
     * @returns int The amount of stock that has been used
     */
    public static function getAccommodationStock(AccommodationInventory $inventory): int
    {
        $used = 0;
        foreach ($inventory->tourComponents as $component) {
            $used += $component->orders()->count();
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
        $used = 0;
        foreach ($inventory->tourComponents as $component) {
            $used += $component->orders()->count();
        }
        return $used;
    }

    /**
     * Get the amount of used stock for a FlightInventory
     * @param FlightInventory $inventory The inventory to check
     * @return int The amount of stock that has been used
     */
    public static function getFlightStock(FlightInventory $inventory): int
    {
        $used = 0;
        foreach ($inventory->flightInventoryTour as $component) {
            $used += $component->orders()->count();
        }
        return $used;
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
            $used += $component->orders()->count();
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
            $used += $order->orderCustomers()->count();
        }
        return $used;
    }

}
