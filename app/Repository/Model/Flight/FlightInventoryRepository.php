<?php

namespace App\Repository\Model\Flight;

use App\Models\Flight\FlightInventory;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class FlightInventoryRepository extends InventoryRepository
{
    private FlightInventory $inventory;
    
    public function __construct(FlightInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->departs_at;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->arrives_at;
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        $from->setTime(0,0);
        $to->setTime(11,59,59);
        $inventories = [];
        if (isset($tour)) {
            foreach ($tour->flightInventoryTours as $inventoryTour) {
                $inventories[] = $inventoryTour->inventory->id;
            }
        }
        return FlightInventory::whereBetween('departs_at', [$from, $to])->whereBetween('arrives_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function getUsedStock(): int
    {
        $query = DB::table('order_flights');
        $query->join('flight_inventory_tours', 'order_flights.flight_inventory_tour_id', '=', 'flight_inventory_tours.id');
        $query->join('flight_inventories', 'flight_inventory_tours.flight_inventory_id', '=', 'flight_inventories.id');
        $query->join('order_customers', 'order_flights.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('flight_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_flights.deleted_at');
        return $query->selectRaw("count(order_flights.id) as 'used_stock'")->first()->used_stock;
    }

    public function getTotalStock(): int
    {
        return $this->inventory->stock;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function get(): FlightInventory
    {
        return $this->inventory;
    }

    public function update(array $data): FlightInventory
    {
        $this->inventory->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->inventory->save();
    }

    public function delete(): bool
    {
        return $this->inventory->delete();
    }

    public function isDeleted(): bool
    {
        return $this->inventory->trashed();
    }

    public function __toString(): string
    {
        return "{$this->inventory->component} - {$this->inventory->flight_number} ({$this->inventory->travelClass}) (" . f_datetime($this->inventory->departs_at) . " to " . f_datetime($this->inventory->arrives_at) . ")";
    }
}