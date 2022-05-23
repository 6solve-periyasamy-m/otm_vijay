<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use StringFormatter;

class AccommodationInventoryTourRepository extends InventoryTourRepository
{
    private AccommodationInventoryTour $inventoryTour;

    public function __construct(AccommodationInventoryTour $inventoryTour)
    {
        $this->inventoryTour = $inventoryTour;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->accommodationInventory->accommodation->name;
                $components[$component->id]['room_type'] = $component->accommodationInventory->roomType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderAccommodation as $oComponent) {
                $component = $oComponent->accommodationInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null): Collection
    {
        $inventories = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return AccommodationInventory::whereBetween('check_in', [$dateFrom, $dateTo])->whereBetween('check_out', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        $group = $orderCustomer->primary_group;
        if (!isset($group)) return null;
        $orderComponent = OrderAccommodation::create([
            'group_id' => $group->id,
            'accommodation_inventory_tour_id' => $this->inventoryTour,
            'share_with_user_id' => null,
            'cost' => $this->inventoryTour->tour_sales_price,
        ]);
        //event(new OrderCustomerAccommodationAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): AccommodationInventoryTour
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $this->inventoryTour->id)->first();
        if (!isset($upgrade)) return $this->inventoryTour;
        return $upgrade->base;
    }

    /**
     * @param ComponentUpgradeRepository $upgradeRepository Expected AccommodationInventoryTourUpgradeRepository
     * @return bool
     */
    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof AccommodationInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->inventoryTour->id) return true;
        foreach ($this->inventoryTour->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): AccommodationInventoryTour
    {
        return $this->inventoryTour;
    }

    public function update(array $data): AccommodationInventoryTour
    {
        $this->inventoryTour->update($data);
        $this->save();
        return $this->inventoryTour;
    }

    public function save(): bool
    {
        return $this->inventoryTour->save();
    }

    public function delete(): bool
    {
        return $this->inventoryTour->delete();
    }

    public function isDeleted(): bool
    {
        return $this->inventoryTour->trashed();
    }

    public function __toString(): string
    {
        $inventory = $this->inventoryTour->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }
}