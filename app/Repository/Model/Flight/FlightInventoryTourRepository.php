<?php

namespace App\Repository\Model\Flight;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingFlight;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Interfaces\Manifest\HasFlightManifest;
use App\Repository\Model\Order\Component\OrderFlightRepository;
use App\Repository\Model\Quote\Component\QuoteFlightRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Traits\Component\IsFlight;
use Icon;
use Illuminate\Support\Collection;

class FlightInventoryTourRepository extends InventoryTourRepository implements HasFlightManifest
{
    use IsFlight;

    private FlightInventoryTour $tourComponent;

    public function __construct(FlightInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->flightInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->flightInventory->flight_number;
                $components[$component->id]['travel_class'] = $component->flightInventory->travelClass->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderFlights as $oComponent) {
                $component = $oComponent->flightInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    /**
     * @param OrderCustomer $orderCustomer
     * @param bool $silent
     * @return OrderFlightRepository|null
     */
    public function grantToCustomer(OrderCustomer $orderCustomer, bool $silent = false): ?OrderFlightRepository
    {
        $orderComponent = OrderFlight::make([
            'order_customer_id' => $orderCustomer->id,
            'flight_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price ?? 0,
            'estimated_purchase_price' => $this->tourComponent->inventory->local_purchase_price,
        ]);
        $silent ? $orderComponent->saveQuietly() : $orderComponent->save();
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): FlightInventoryTour
    {
        $upgrade = FlightInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof FlightInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): FlightInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): FlightInventoryTour
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
        $inventory = $this->tourComponent->flightInventory;
        $component = $inventory->flight;
        return $component->airline->name . ' (' . $inventory->flight_number . ') ' . $component->departureAirport->name . ' to ' . $component->arrivalAirport->name .
            ' (' . f_datetime($inventory->departs_at) . ' to ' . f_datetime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $active = $this->getActiveComponent($this, $traveller);
        if ($active !== null) return $active;
        $this->getActiveUpgrade($traveller)?->getBookingComponent($traveller)?->delete();
        $bookingComponent = BookingFlight::create([
            'booking_traveller_id' => $traveller->id,
            'flight_inventory_tour_id' => $this->tourComponent->id,
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
        $component = $orderCustomer->orderFlights()->where('flight_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->flights()->where('flight_inventory_tour_id', $this->tourComponent->id)->first();
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
     * @return Collection<FlightInventoryTour>
     */
    public function getAvailableForUpgrade(): \Illuminate\Support\Collection
    {
        $tour = $this->tourComponent->tour;
        return FlightInventoryRepository::getBetweenDates($tour->date_from->setTime(0,0), $tour->date_to->setTime(23,59,59), $tour->repository);
    }

    public function getUpgradeId(): int
    {
        $upgrade = FlightInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = FlightInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?FlightInventoryRepository
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

    public function addToQuote(Quote $quote): ?QuoteFlightRepository
    {
        $component = QuoteFlight::create([
            'flight_inventory_id' => $this->tourComponent->flight_inventory_id,
            'quote_id' => $quote->id,
            'tour_component_type' => $this->tourComponent->tour_component_type,
            'tour_sales_price' => $this->tourComponent->tour_sales_price,
            'flight_type' => $this->tourComponent->flight_type,
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

    public function getFlightManifest(): Collection|array
    {
        return $this->tourComponent->orders()->with(FlightManifestRepository::getRelations())->get();
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
            $upgrade = FlightInventoryTourUpgrade::where('upgrade_id', '=', $tourComponent->id)->first();
            $upgradeName = "$upgrade->description - " . f_currency($tourComponent->tour_sales_price);
        }
        return new ComponentInformation(
            "{$component->departureAirport->name}, {$component->departureAirport->address->country?->name} to {$component->arrivalAirport->name}, {$component->arrivalAirport->address->country?->name}",
            "Flight from {$component->departureAirport->name} to {$component->arrivalAirport->name}",
            $component->image_url,
            $inventory->departs_at,
            $inventory->arrives_at,
            Icon::flight(),
            $inventory->external_notes,
            $upgradeName,
            [
                'Airline' => $component->airline->name,
                'Check In' => f_datetime($inventory->check_in),
                'Departure' => f_datetime($inventory->departs_at),
                'Arrival' => f_datetime($inventory->arrives_at),
                'Flight Number' => $inventory->flight_number,
                'Travel Class' => $inventory->travelClass->__toString(),
            ]
        );
    }

    public function getStockUsedOnBooking(Booking $booking): int
    {
        return $booking->flights()->where('flight_inventory_tour_id', '=', $this->tourComponent->id)->count();
    }

    public function getCostToCustomer(): float
    {
        return $this->tourComponent->tour_component_type === 'Included' ? 0 : $this->tourComponent->tour_sales_price;
    }

    public static function find($id): FlightInventoryTour|null
    {
        return FlightInventoryTour::find($id);
    }
}
