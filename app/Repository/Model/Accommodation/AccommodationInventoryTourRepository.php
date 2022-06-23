<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingAccommodation;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;

class AccommodationInventoryTourRepository extends InventoryTourRepository
{
    private AccommodationInventoryTour $tourComponent;

    public function __construct(AccommodationInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->accommodationInventoryTours()
                     ->with('accommodationInventory', 'accommodationInventory.accommodation')
                     ->get() as $component) {
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

    public function get(): AccommodationInventoryTour
    {
        return $this->tourComponent;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        $group = $orderCustomer->primary_group;
        if (!isset($group)) return null;
        $orderComponent = OrderAccommodation::create([
            'group_id' => $group->id,
            'accommodation_inventory_tour_id' => $this->tourComponent,
            'share_with_user_id' => null,
            'cost' => $this->tourComponent->tour_sales_price,
        ]);
        //event(new OrderCustomerAccommodationAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): AccommodationInventoryTour
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
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
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function update(array $data): AccommodationInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->tourComponent;
    }

    public function save(): bool
    {
        return $this->tourComponent->save();
    }

    public function delete(): bool
    {
        return $this->tourComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->tourComponent->trashed();
    }

    public function __toString(): string
    {
        $inventory = $this->tourComponent->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . f_datetime($inventory->check_in) . ' to ' . f_datetime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }

    public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = BookingAccommodation::create([
            'booking_group_id' => $traveller->primary_group->id,
            'accommodation_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $component->repository;
    }

    public function getUsedStock(): int
    {
        return $this->tourComponent->inventory->repository->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->tourComponent->inventory->repository->getTotalStock();
    }

    public function getAvailableStock(): int
    {
        return $this->tourComponent->inventory->repository->getAvailableStock();
    }

    public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        foreach ($orderCustomer->orderAccommodation as $accommodation) {
            if ($accommodation->accommodation_inventory_tour_id == $this->tourComponent->id) return $accommodation->repository;
        }
        return null;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        foreach ($traveller->accommodation as $accommodation) {
            if ($accommodation->accommodation_inventory_tour_id == $this->tourComponent->id) return $accommodation->repository;
        }
        return null;
    }

    public function getComponentString(): string
    {
        return 'accommodation';
    }

    public function getCost(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function getComponentType(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getAvailableForUpgrade(): array
    {
        $tour = $this->tourComponent->tour;
        $included = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $included[$inventoryTour->accommodationInventory->id] = $inventoryTour->accommodationInventory->id;
        }
        $data = [];
        foreach ($this->tourComponent->accommodationInventory->accommodation->inventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->check_in->gte($tour->date_from->setTime(0, 0)) && $inventory->check_out->lte($tour->date_to->setTime(23, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public function getUpgradeId(): int
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?InventoryRepository
    {
        return $this->tourComponent->inventory->repository;
    }
}
