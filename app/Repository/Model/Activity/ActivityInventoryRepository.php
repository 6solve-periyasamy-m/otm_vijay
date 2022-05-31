<?php

namespace App\Repository\Model\Activity;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ActivityInventoryRepository extends InventoryRepository
{
    private ActivityInventory $inventory;
    
    public function __construct(ActivityInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->starts_at;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->ends_at;
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        $from->setTime(0,0);
        $to->setTime(11,59,59);
        $inventories = [];
        if (isset($tour)) {
            foreach ($tour->activityInventoryTours as $inventoryTour) {
                $inventories[] = $inventoryTour->inventory->id;
            }
        }
        return ActivityInventory::whereBetween('starts_at', [$from, $to])->whereBetween('ends_at', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function getUsedStock(): int
    {
        $used = 0;
        foreach ($this->inventory->tourComponents as $component) {
            foreach ($component->orders as $orderComponent) {
                if (!$orderComponent->cancelled) $used++;
            }
        }
        return $used;
    }

    public function getTotalStock(): int
    {
        return $this->inventory->stock;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function get(): ActivityInventory
    {
        return $this->inventory;
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
}