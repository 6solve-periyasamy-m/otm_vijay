<?php

namespace App\Repository\Abstracts;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Flight\FlightInventory;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Repository\Interfaces\HasComponentType;
use App\Repository\Interfaces\HasStockControl;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class InventoryRepository extends ModelRepository implements HasStockControl, HasComponentType
{
    public abstract function getStartTime(): Carbon|null;

    public abstract function getEndTime(): Carbon|null;

    public static abstract function getBetweenDates(Carbon $from, Carbon $to, ComponentPackageRepository $repository = null): Collection;

    public abstract function addToTour(Tour $tour, string $tourComponentType, float $price = -1): ?InventoryTourRepository;

    public abstract function addToQuote(Quote $quote, string $tourComponentType, float $price = -1): ?QuoteComponentRepository;

    public abstract function getPurchasePrice(): float;

    public abstract function getSalesPrice(): ?float;

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
