<?php

namespace App\Repository\Abstracts;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Flight\FlightInventory;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Repository\Abstracts\Interfaces\BelongsOnItinerary;
use App\Repository\Interfaces\HasComponentType;
use App\Repository\Interfaces\HasStockControl;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class InventoryRepository extends ModelRepository implements HasStockControl, HasComponentType, BelongsOnItinerary
{
    abstract public function getStartTime(): Carbon|null;

    abstract public function getEndTime(): Carbon|null;

    abstract public static function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository|null $repository = null): Collection;

    abstract public function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?InventoryTourRepository;

    abstract public function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteComponentRepository;

    abstract public function getPurchasePrice(): float;

    abstract public function getSalesPrice(): ?float;

    abstract public function getPurchasePriceString(): string;

    abstract public function getLocalPurchasePrice(): ?float;

    abstract public function getDependants(): int;

    public static function getComponent(string $type, int $id): ?InventoryRepository
    {
        return match ($type) {
            'accommodation' => AccommodationInventory::find($id)?->repository,
            'activity' => ActivityInventory::find($id)?->repository,
            'flight' => FlightInventory::find($id)?->repository,
            'transport' => TransportInventory::find($id)?->repository,
            'merchandise' => MerchandiseInventory::find($id)?->repository,
            default => null,
        };
    }
}
