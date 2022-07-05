<?php

namespace App\Repository\Model\Transport;

use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Repository\Abstracts\InventoryRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class TransportInventoryRepository extends InventoryRepository
{
    private TransportInventory $inventory;

    public function __construct(TransportInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59, 59);
        $inventories = [];
        if (isset($tour)) {
            foreach ($tour->flightInventoryTours as $inventoryTour) {
                $inventories[] = $inventoryTour->inventory->id;
            }
        }
        return TransportInventory::whereBetween('departs_at', [$from, $to])->whereBetween('arrives_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): TransportInventory
    {
        return $this->inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->departs_at;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->arrives_at;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->inventory->stock;
    }

    public function getUsedStock(): int
    {
        $query = DB::table('order_transports');
        $query->join('transport_inventory_tours', 'order_transports.transport_inventory_tour_id', '=', 'transport_inventory_tours.id');
        $query->join('transport_inventories', 'transport_inventory_tours.transport_inventory_id', '=', 'transport_inventories.id');
        $query->join('order_customers', 'order_transports.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('transport_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_transports.deleted_at');
        return $query->selectRaw("count(order_transports.id) as 'used_stock'")->first()->used_stock;
    }

    public function update(array $data): TransportInventory
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
        return "{$this->inventory->component} - {$this->inventory->travelClass} (" . f_datetime($this->inventory->departs_at) . " to " . f_datetime($this->inventory->arrives_at) . ")";
    }
}
