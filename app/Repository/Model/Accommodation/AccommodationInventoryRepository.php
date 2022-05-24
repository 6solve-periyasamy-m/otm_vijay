<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use StringFormatter;

class AccommodationInventoryRepository extends InventoryRepository
{
    private AccommodationInventory $inventory;
    
    public function __construct(AccommodationInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function getStartTime(): Carbon
    {
        return $this->inventory->check_in;
    }

    public function getEndTime(): Carbon
    {
        return $this->inventory->check_out;
    }

    public static function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection
    {
        $from->setTime(0,0);
        $to->setTime(11,59,59);
        $inventories = [];
        if (isset($tour)) {
            foreach ($tour->accommodationInventoryTours as $inventoryTour) {
                $inventories[] = $inventoryTour->inventory->id;
            }
        }        
        return AccommodationInventory::whereBetween('check_in', [$from, $to])->whereBetween('check_out', [$from, $to])->whereNotIn('id', $inventories)->get();
    }

    public function get(): AccommodationInventory
    {
        return $this->inventory;
    }

    public function update(array $data): AccommodationInventory
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
        return "{$this->inventory->component} - {$this->inventory->roomType} {$this->inventory->boardType} (" . StringFormatter::formatDateTime($this->inventory->check_in) . " to " . StringFormatter::formatDateTime($this->inventory->check_out) . ")";
    }
}