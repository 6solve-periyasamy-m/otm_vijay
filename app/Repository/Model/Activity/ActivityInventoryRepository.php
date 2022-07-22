<?php

namespace App\Repository\Model\Activity;

use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class ActivityInventoryRepository extends InventoryRepository
{
    private ActivityInventory $inventory;

    public function __construct(ActivityInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59, 59);
        $inventories = [];
        if (isset($tour)) {
            foreach ($tour->activityInventoryTours as $inventoryTour) {
                $inventories[] = $inventoryTour->inventory->id;
            }
        }
        return ActivityInventory::whereBetween('starts_at', [$from, $to])->whereBetween('ends_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): ActivityInventory
    {
        return $this->inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->starts_at;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->ends_at;
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
        $query = DB::table('order_activities');
        $query->join('activity_inventory_tours', 'order_activities.activity_inventory_tour_id', '=', 'activity_inventory_tours.id');
        $query->join('activity_inventories', 'activity_inventory_tours.activity_inventory_id', '=', 'activity_inventories.id');
        $query->join('order_customers', 'order_activities.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('activity_inventories.id', '=', $this->inventory->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_activities.deleted_at');
        return $query->selectRaw("count(order_activities.id) as 'used_stock'")->first()->used_stock;
    }

    public function update(array $data): ActivityInventory
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
        return "{$this->inventory->component} - {$this->inventory->ticketType} (" . f_datetime($this->inventory->starts_at) . " to " . f_datetime($this->inventory->ends_at) . ")";
    }

    public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?ActivityInventoryTourRepository
    {
        $inventoryTour = ActivityInventoryTour::make([
            'tour_sales_price' => $price == -1 ? $this->inventory->sales_price : $price,
            'tour_component_type' => $tourComponentType,
            'tour_id' => $tour->id,
        ]);
        $this->inventory->tourComponents()->save($inventoryTour);
        return $inventoryTour;
    }
}
