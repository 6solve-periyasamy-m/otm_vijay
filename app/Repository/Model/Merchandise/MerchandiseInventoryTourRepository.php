<?php

namespace App\Repository\Model\Merchandise;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Model\Quote\Component\QuoteMerchandiseRepository;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsMerchandise;
use Auth;

class MerchandiseInventoryTourRepository extends InventoryTourRepository
{
    use IsMerchandise;

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

    public function grantToCustomer(OrderCustomer $orderCustomer, bool $silent = false): ?OrderComponentRepository
    {
        $orderComponent = OrderMerchandise::make([
            'order_customer_id' => $orderCustomer->id,
            'merchandise_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price ?? 0,
            'estimated_purchase_price' => $this->tourComponent->inventory->local_purchase_price,
        ]);
        $silent ? $orderComponent->saveQuietly() : $orderComponent->save();
        return $orderComponent->repository;
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

    public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $active = $this->getActiveComponent($this, $traveller);
        if ($active !== null) return $active;
        $bookingComponent = BookingMerchandise::create([
            'booking_traveller_id' => $traveller->id,
            'merchandise_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $bookingComponent->repository;
    }

    public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        $component = $orderCustomer->orderMerchandise()->where('merchandise_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->merchandise()->where('merchandise_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getCost(): float
    {
        return $this->tourComponent->tour_sales_price ?? 0;
    }

    public function getTourComponentType(): string
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
        foreach ($this->tourComponent->orderComponents as $orderComponent) {
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

    public function addToQuote(Quote $quote): ?QuoteMerchandiseRepository
    {
        $component = QuoteMerchandise::create([
            'quote_id' => $quote->id,
            'merchandise_inventory_id' => $this->tourComponent->merchandise_inventory_id,
            'tour_sales_price' => $this->tourComponent->tour_sales_price,
            'tour_component_type' => $this->tourComponent->tour_component_type,
        ]);
        return $component->repository;
    }

    public function isStockControlActive(): bool
    {
        return $this->tourComponent->stock_control_active ?? false;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return !($this->isStockControlActive() && $this->getAvailableStock() < $amount);
    }

    public function getComponentInformation(): ComponentInformation
    {
        $tourComponent = $this->tourComponent;
        $inventory = $tourComponent->inventory;
        $component = $inventory->component;
        $name = "{$component->name} ({$inventory->variant}) ({$inventory->size})";
        return new ComponentInformation(
            $name,
            "A {$inventory->size} {$inventory->variant} {$component->type}",
            $component->image_url,
            null,
            null
        );
    }

    public function getStockUsedOnBooking(Booking $booking): int
    {
        return $booking->merchandise()->where('merchandise_inventory_tour_id', '=', $this->tourComponent->id)->count();
    }

    public function getCostToCustomer(): float
    {
        return $this->tourComponent->tour_component_type === 'Included' ? 0 : $this->tourComponent->tour_sales_price;
    }

    public static function find($id): MerchandiseInventoryTour|null
    {
        return MerchandiseInventoryTour::find($id);
    }

    public function getItineraryItem(int|null $quantity = null): ItineraryItem
    {
        return $this->getInventory()?->getItineraryItem($quantity);
    }

    public function getOverview(): string
    {
        $inventory = $this->tourComponent->inventory;
        $component = $inventory->component;
        return $component->name . ' - ' . $inventory->size . ' - ' . $inventory->variant;
    }

    public function getUpdateLink(): string|null
    {
        if (Auth::user()?->can('update', $this->tourComponent)) {
            return route('merchandise.inventory.tour.edit', [
                'tour' => $this->tourComponent->tour_id,
                'inventoryTour' => $this->tourComponent
            ]);
        }
        return null;
    }

    public function getDeleteLink(): string|null
    {
        if (Auth::user()?->can('delete', $this->tourComponent)) {
            return route('merchandise.inventory.tour.delete', [
                'tour' => $this->tourComponent->tour_id,
                'inventoryTour' => $this->tourComponent
            ]);
        }
        return null;
    }

    public function getRestoreLink(): string|null
    {
        if (Auth::user()?->can('delete', $this->tourComponent)) {
            return route('merchandise.inventory.tour.restore', [
                'tour' => $this->tourComponent->tour_id,
                'inventoryTour' => $this->tourComponent
            ]);
        }
        return null;
    }
}
