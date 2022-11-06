<?php

namespace App\Repository\Abstracts;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\BookingTraveller;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Interfaces\HasStockControl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class InventoryTourRepository extends InventoryContainerRepository implements HasStockControl
{
    public static abstract function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array;

    public abstract function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository;

    public abstract function addToQuote(Quote $quote): ?QuoteComponentRepository;

    public abstract function getUpgradeParent(): Model;

    public abstract function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool;

    public abstract function getAvailableForUpgrade(): array|Collection;

    public abstract function getUpgradeId(): int;

    public abstract function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository;

    public abstract function isBookable(): bool;

    public abstract function getUsedOnOrderCount(): int;

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
            if ($required > 0 && $upgrade->upgrade->repository->getAvailableStock() < $required) continue;
            if (!$downgrade && $upgrade->upgrade->tour_sales_price < $cost) continue;
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
}
