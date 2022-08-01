<?php

namespace App\Repository\Abstracts;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Repository\Interfaces\HasComponentType;

abstract class OrderComponentRepository extends ModelRepository implements HasComponentType
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
    public abstract function getTourComponent(): ?InventoryTourRepository;
    public abstract function getItineraryItems(): array;

    public static function getComponent(string $type, int $id): ?OrderComponentRepository
    {
        return match ($type) {
            'accommodation' => OrderAccommodation::find($id)?->repository,
            'activity' => OrderActivity::find($id)?->repository,
            'flight' => OrderFlight::find($id)?->repository,
            'transport' => OrderTransport::find($id)?->repository,
            'merchandise' => OrderMerchandise::find($id)?->repository,
            default => null,
        };
    }
}
