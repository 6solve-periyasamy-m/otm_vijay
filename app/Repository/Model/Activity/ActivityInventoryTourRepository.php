<?php

namespace App\Repository\Model\Activity;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingActivity;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Interfaces\HasStockControl;
use App\Repository\Model\Order\Component\OrderActivityRepository;

class ActivityInventoryTourRepository extends InventoryTourRepository
{
    private ActivityInventoryTour $tourComponent;

    public function __construct(ActivityInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->activityInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->activityInventory->activity->name;
                $components[$component->id]['activity_type'] = $component->activityInventory->activity->activityType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderActivities as $oComponent) {
                $component = $oComponent->activityInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderActivityRepository
    {
        $orderComponent = OrderActivity::create([
            'order_customer_id' => $orderCustomer->id,
            'activity_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): ActivityInventoryTour
    {
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): ActivityInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): ActivityInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->get();
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
        $inventory = $this->tourComponent->activityInventory;
        $component = $inventory->activity;
        return $component->name . ' (' . f_datetime($inventory->starts_at) . ' to ' . f_datetime($inventory->ends_at) . ') (' . $inventory->ticketType->name . ')';
    }

    public function grantToTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $bookingComponent = BookingActivity::create([
            'booking_traveller_id' => $traveller->id,
            'activity_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $bookingComponent->repository;
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
        $component = $orderCustomer->orderActivities()->where('activity_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->activities()->where('activity_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getComponentString(): string
    {
        return 'activity';
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
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            $included[$inventoryTour->activityInventory->id] = $inventoryTour->activityInventory->id;
        }
        $data = [];
        foreach ($this->tourComponent->activityInventory->activity->activityInventory as $inventory) {
            if (in_array($inventory->id, $included)) continue;
            if ($inventory->starts_at->gte($tour->date_from->setTime(0,0)) && $inventory->ends_at->lte($tour->date_to->setTime(23, 59, 59))) {
                $data[$inventory->id] = $inventory;
            }
        }
        return $data;
    }

    public function getUpgradeId(): int
    {
        $upgrade = ActivityInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }
}
