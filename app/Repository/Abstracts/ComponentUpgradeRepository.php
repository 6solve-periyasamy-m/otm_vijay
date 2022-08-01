<?php

namespace App\Repository\Abstracts;

use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Interfaces\HasComponentType;

abstract class ComponentUpgradeRepository extends ModelRepository implements HasComponentType
{
    public static function getComponent(string $type, int $id): ?ComponentUpgradeRepository
    {
        return match ($type) {
            'accommodation' => AccommodationInventoryTourUpgrade::find($id)?->repository,
            'activity' => ActivityInventoryTourUpgrade::find($id)?->repository,
            'flight' => FlightInventoryTourUpgrade::find($id)?->repository,
            'transport' => TransportInventoryTourUpgrade::find($id)?->repository,
            default => null,
        };
    }
}
