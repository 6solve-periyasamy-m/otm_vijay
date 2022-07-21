<?php

namespace App\Repository\Abstracts;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\BookingTraveller;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Quote;
use App\Models\Tour\Merchandise;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Interfaces\HasStockControl;
use Illuminate\Database\Eloquent\Model;

abstract class InventoryTourRepository extends ModelRepository implements HasStockControl
{
    public static abstract function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array;

    public abstract function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository;

    public abstract function addToQuote(Quote $quote): ?QuoteComponentRepository;

    public abstract function getUpgradeParent(): Model;

    public abstract function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool;

    public abstract function getAvailableForUpgrade(): array;

    public abstract function getUpgradeId(): int;

    public abstract function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository;

    public abstract function getComponentString(): string;

    public abstract function getCost(): float;

    public abstract function getComponentType(): string;

    public abstract function isBookable(): bool;

    public abstract function getInventory(): ?InventoryRepository;

    public abstract function getUsedOnOrderCount(): int;

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
}
