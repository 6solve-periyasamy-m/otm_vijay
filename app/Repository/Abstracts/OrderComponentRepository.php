<?php

namespace App\Repository\Abstracts;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;

abstract class OrderComponentRepository extends ModelRepository
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;

    public static function getComponent(string $type, int $id): ?OrderComponentRepository
    {
        return match ($type) {
            'accommodation' => OrderAccommodation::find($id)?->repository,
            'activity' => OrderActivity::find($id)?->repository,
            'flight' => OrderFlight::find($id)?->repository,
            'transport' => OrderTransport::find($id)?->repository,
            'extra' => OrderMerchandise::find($id)?->repository,
            default => null,
        };
    }
}
