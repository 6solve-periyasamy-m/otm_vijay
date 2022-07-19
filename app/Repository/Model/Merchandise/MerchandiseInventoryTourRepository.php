<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use Log;

class MerchandiseInventoryTourRepository extends InventoryTourRepository
{
    private MerchandiseInventoryTour $tourComponent;

    public function __construct(MerchandiseInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->merchandise as $component) {
            if ($component->available_stock <= 0) continue;
            $components[$component->id] = [];
            $components[$component->id]['id'] = $component->id;
            $components[$component->id]['name'] = $component->name;
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderMerchandise as $oComponent) {
                $component = $oComponent->merchandise;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        return OrderMerchandise::create([
            'order_customer_id' => $orderCustomer->id,
            'merchandise_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price,
        ])->repository;
    }

    public function getUpgradeParent(): MerchandiseInventoryTour
    {
        return $this->tourComponent; // Merchandise do not have upgrades
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        return false; // Merchandise do not have upgrades
    }

    public function update(array $data): MerchandiseInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->tourComponent->save();
    }

    public function get(): MerchandiseInventoryTour
    {
        return $this->tourComponent;
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
        return "{$this->tourComponent->inventory->component->name} ({$this->tourComponent->inventory->variant->name}) (" . $this->tourComponent->inventory->size?->name ?? 'No Size'  . ")";
    }

    public function grantToTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        Log::info($traveller);
        $bookingComponent = BookingMerchandise::create([
            'booking_traveller_id' => $traveller->id,
            'merchandise_id' => $this->tourComponent->id,
        ]);
        return $bookingComponent->repository;
    }

    public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        $component = $orderCustomer->orderMerchandise()->where('merchandise_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->merchandise()->where('merchandise_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getComponentString(): string
    {
        return 'merchandise';
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
        return [];
    }

    public function getUpgradeId(): int
    {
        return -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?InventoryRepository
    {
        return $this->tourComponent->inventory->repository;
    }

    public function getUsedOnOrderCount(): int
    {
        $used = 0;
        foreach ($this->tourComponent->orderMerchandise as $orderComponent) {
            if (!$orderComponent->cancelled) $used++;
        }
        return $used;
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
}
