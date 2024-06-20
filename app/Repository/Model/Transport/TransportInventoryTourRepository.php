<?php

namespace App\Repository\Model\Transport;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingTransport;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Model\Order\Component\OrderTransportRepository;
use App\Repository\Model\Quote\Component\QuoteTransportRepository;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Storage\Order\ItineraryItem;
use App\Repository\Traits\Component\IsTransport;
use Icon;
use Illuminate\Support\Collection;

class TransportInventoryTourRepository extends InventoryTourRepository
{
    use IsTransport;

    private TransportInventoryTour $tourComponent;

    public function __construct(TransportInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->transportInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->transportInventory->transport->name;
                $components[$component->id]['transport_type'] = $component->transportInventory->transport->transportType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderTransports as $oComponent) {
                $component = $oComponent->flightInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer, bool $silent = false): ?OrderTransportRepository
    {
        $orderComponent = OrderTransport::make([
            'order_customer_id' => $orderCustomer->id,
            'transport_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price ?? 0,
            'estimated_purchase_price' => $this->tourComponent->inventory->local_purchase_price,
        ]);
        $silent ? $orderComponent->saveQuietly() : $orderComponent->save();
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): TransportInventoryTour
    {
        $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof TransportInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): TransportInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): TransportInventoryTour
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
        $inventory = $this->tourComponent->transportInventory;
        $component = $inventory->transport;
        return $component->name . ' (' . $component->departureAddress->name . ' to ' . $component->arrivalAddress->name . ')' .
            ' (' . $component->transportType->name . ') ' .
            ' (' . f_datetime($inventory->departs_at) . ' to ' . f_datetime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $active = $this->getActiveComponent($this, $traveller);
        if ($active !== null) return $active;
        $this->getActiveUpgrade($traveller)?->getBookingComponent($traveller)?->delete();
        $bookingComponent = BookingTransport::create([
            'booking_traveller_id' => $traveller->id,
            'transport_inventory_tour_id' => $this->tourComponent->id,
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
        $component = $orderCustomer->orderTransports()->where('transport_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->transport()->where('transport_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getCost(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function getTourComponentType(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    /**
     * @return Collection<TransportInventoryTour>
     */
    public function getAvailableForUpgrade(): Collection
    {
        $tour = $this->tourComponent->tour;
        return TransportInventoryRepository::getBetweenDates($tour->date_from->setTime(0,0), $tour->date_to->setTime(23,59,59), $tour->repository);
    }

    public function getUpgradeId(): int
    {
        $upgrade = TransportInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?TransportInventoryRepository
    {
        return $this->tourComponent->inventory->repository;
    }

    public function getUsedOnOrderCount(): int
    {
        $used = 0;
        foreach ($this->tourComponent->orders as $orderComponent) {
            if (!$orderComponent->cancelled) $used++;
        }
        return $used;
    }

    public function addToQuote(Quote $quote): ?QuoteTransportRepository
    {
        $component = QuoteTransport::create([
            'transport_inventory_id' => $this->tourComponent->transport_inventory_id,
            'quote_id' => $quote->id,
            'tour_component_type' => $this->tourComponent->tour_component_type,
            'tour_sales_price' => $this->tourComponent->tour_sales_price,
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
        if ($tourComponent->tour_component_type === 'Add-on') {
            $upgradeName = "Add-on";
        } elseif ($tourComponent->tour_component_type === 'Included') {
            $upgradeName = "Included";
        } else {
            $upgrade = TransportInventoryTourUpgrade::where('upgrade_id', '=', $tourComponent->id)->first();
            $upgradeName = "$upgrade->description - " . f_currency($tourComponent->tour_sales_price);
        }
        return new ComponentInformation(
            $component->name,
            $component->description,
            $component->image_url,
            $inventory->departs_at,
            $inventory->arrives_at,
            Icon::transport(),
            $inventory->external_notes,
            $upgradeName,
            [
                'Transport Number' => $inventory->transport_number,
                'Transport Type' => $component->transportType->name,
                'Departure' => f_datetime($inventory->departs_at),
                'Arrival' => f_datetime($inventory->arrives_at),
                'Travel Class' => $inventory->travelClass->__toString(),
            ]
        );
    }

    public function getStockUsedOnBooking(Booking $booking): int
    {
        return $booking->transport()->where('transport_inventory_tour_id', '=', $this->tourComponent->id)->count();
    }

    public function getCostToCustomer(): float
    {
        return $this->tourComponent->tour_component_type === 'Included' ? 0 : $this->tourComponent->tour_sales_price;
    }

    public static function find($id): TransportInventoryTour|null
    {
        return TransportInventoryTour::find($id);
    }

    public function getItineraryItem(): ItineraryItem
    {
        return $this->getInventory()?->getItineraryItem();
    }
}
