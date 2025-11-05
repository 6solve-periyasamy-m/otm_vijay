<?php

namespace App\Repository\Storage\Report;

use App\Models\Accommodation\AccommodationInventory;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccommodationByNightReportStorage implements FromView
{
    /** @var AccommodationInventory[] */
    public array $inventory = [];

    public function __construct()
    {

    }

    public function addAll(array $inventories): static
    {
        foreach ($inventories as $inventory) {
            $this->inventory[$inventory->id] = $inventory;
        }
        usort($this->inventory, static function (AccommodationInventory $a, AccommodationInventory $b) {
            return AccommodationInventoryRepository::compareTwo($a, $b);
        });
        return $this;
    }

    public function add(AccommodationInventory $inventory): static
    {
        $this->inventory[$inventory->id] = $inventory;
        usort($this->inventory, static function (AccommodationInventory $a, AccommodationInventory $b) {
            return AccommodationInventoryRepository::compareTwo($a, $b);
        });
        return $this;
    }

    public function getPeriod(): CarbonPeriod
    {
        $first = $this->inventory[array_key_first($this->inventory)];
        $last = $this->inventory[array_key_last($this->inventory)];
        return CarbonPeriod::create($first->check_in->setTime(23, 59, 59), '1 day', $last->check_out->setTime(0, 0, 0));
    }

    /**
     * Get the stored inventory grouped by hotel, then by room, board and category
     * @return array<int, array<int, AccommodationInventory[]>
     */
    public function getGrouped(): array
    {
        $grouped = [];
        foreach ($this->inventory as $inventory) {
            if (!array_key_exists($inventory->accommodation_id, $grouped)) { $grouped[$inventory->accommodation_id] = []; }
            $cid = $inventory->room_category_id === null ? "null" : (string)$inventory->room_category_id;
            $key = "{$inventory->room_type_id}-{$inventory->board_type_id}-{$cid}";
            if (!array_key_exists($key, $grouped[$inventory->accommodation_id])) { $grouped[$inventory->accommodation_id][$key] = []; }
            $grouped[$inventory->accommodation_id][$key][] = $inventory;
        }
        foreach ($grouped as $key => $items) {
            foreach ($items as $iKey => $inventories) {
                if (empty($inventories)) {
                    unset($grouped[$key][$iKey]);
                    continue;
                }
                usort($inventories, static function (AccommodationInventory $a, AccommodationInventory $b) {
                    return AccommodationInventoryRepository::compareTwo($a, $b);
                });
                $grouped[$key][$iKey] = $inventories;
            }
        }
        return $grouped;
    }

    public function view(): View
    {
        return view('partials.reports.tables.accommodation-by-night', ['storage' => $this]);
    }
}