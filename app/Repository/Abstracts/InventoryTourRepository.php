<?php

namespace App\Repository\Abstracts;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Interfaces\HasStockControl;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Storage\Customer\Component\BookingComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class InventoryTourRepository extends InventoryContainerRepository implements HasStockControl
{
    abstract public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array;

    abstract public function grantToCustomer(OrderCustomer $orderCustomer, bool $silent = false): ?OrderComponentRepository;

    abstract public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository;

    abstract public function addToQuote(Quote $quote): ?QuoteComponentRepository;

    abstract public function getUpgradeParent(): Model;

    abstract public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool;

    abstract public function getAvailableForUpgrade(): array|Collection;

    abstract public function getUpgradeId(): int;

    abstract public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    abstract public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository;

    abstract public function isBookable(): bool;

    abstract public function getUsedOnOrderCount(): int;

    abstract public function getComponentInformation(): ComponentInformation;

    abstract public function getStockUsedOnBooking(Booking $booking): int;

    abstract public function getUpdateLink(): string|null;

    abstract public function getDeleteLink(): string|null;

    abstract public function getRestoreLink(): string|null;

    public abstract function getOverview(): string;

    /**
     * Returns the amount of order-components the inventory has
     * @return int
     */
    abstract public function getOrderedCount(): int;

    /**
     * Returns the amount of booking-components the inventory has
     * @return int
     */
    abstract public function getBookedCount(): int;

    public function getCostToCustomer(): float|int
    {
        return $this->getTourComponentType() === 'Included' ? 0 : ($this->get()?->tour_sales_price ?? 0);
    }

    public function getActiveUpgrade(BookingTraveller|OrderCustomer $traveller): InventoryTourRepository|null
    {
        $parent = $this->getUpgradeParent();
        $owned = $this->getActiveComponent($parent->repository, $traveller);
        if ($owned !== null) return $owned->getTourComponent();
        foreach ($parent->upgrades as $upgrade) {
            $component = $this->getActiveComponent($upgrade->upgrade->repository, $traveller);
            if ($component !== null) return $component->getTourComponent();
        }
        return null;
    }

    public function getAbstractBookingComponent(BookingTraveller $traveller): BookingComponent
    {
        return new BookingComponent($this, $traveller);
    }

    public function unbookForAll(Booking $booking): bool
    {
        foreach ($booking->travellers as $traveller) {
            $this->getBookingComponent($traveller)?->delete();
        }
        return true;
    }

    public function bookForAll(Booking $booking): bool
    {
        if (!$this->hasEnoughStock($booking->travellers->count())) return false;
        foreach ($booking->travellers as $traveller) {
            if ($this->getBookingComponent($traveller) !== null) continue;
            $this->grantToBookingTraveller($traveller);
        }
        return true;
    }

    public function purchaseForAll(Order $order): bool
    {
        if (!$this->hasEnoughStock($order->orderCustomers->count())) return false;
        foreach ($order->orderCustomers as $traveller) {
            if ($this->getOrderComponent($traveller) !== null) continue;
            $this->grantToCustomer($traveller);
        }
        return true;
    }

    public function getUpgradeKeyMap(int $required = 0, bool $stock = false, bool $downgrade = true): array
    {
        $component = $this->get();
        $parent = $this->getUpgradeParent();
        if ($component->id == $parent->id) {
            $upgrades = $component->upgrades;
            $cost = 0;
        } else {
            $upgrades = $parent->upgrades;
            $cost = $component->tour_sales_price;
        }
        $data = [];
        if ($downgrade && ($required <= 0 || $parent->repository->getAvailableStock() >= $required)) {
            $data[0] = 'Included - ' . f_currency(0) . ($stock ? ' - Stock ' . $parent->repository->getAvailableStock() . '/' . $parent->repository->getTotalStock() : '');
        }
        foreach ($upgrades as $upgrade) {
            if (!$upgrade->upgrade->is_bookable) continue;
            if ($required > 0 && $upgrade->upgrade->repository->getAvailableStock() < $required) continue;
            if (!$downgrade && $upgrade->upgrade->tour_sales_price < $cost) continue;
            if (!$downgrade &&
                $upgrade->upgrade->repository->getInventory()->get()->id === $this->getInventory()->get()->id) continue;
            $data[$upgrade->id] = $upgrade->description . ' - ' . f_currency($upgrade->upgrade->tour_sales_price)
                . ($stock ? ' - Stock ' . $upgrade->upgrade->repository->getAvailableStock() . '/' . $upgrade->upgrade->repository->getTotalStock() : '');
        }
        return $data;
    }

    public static function getComponent(string $type, int $id): ?InventoryTourRepository
    {
        return match ($type) {
            'accommodation' => AccommodationInventoryTour::find($id)?->repository,
            'activity' => ActivityInventoryTour::find($id)?->repository,
            'flight' => FlightInventoryTour::find($id)?->repository,
            'transport' => TransportInventoryTour::find($id)?->repository,
            'merchandise' => MerchandiseInventoryTour::find($id)?->repository,
            default => null,
        };
    }

    protected function getActiveComponent(InventoryTourRepository $tourComponent, OrderCustomer|BookingTraveller $traveller): OrderComponentRepository|BookingComponentRepository|null
    {
        if ($traveller instanceof OrderCustomer) {
            return $tourComponent->getOrderComponent($traveller);
        } else {
            return $tourComponent->getBookingComponent($traveller);
        }
    }
}
