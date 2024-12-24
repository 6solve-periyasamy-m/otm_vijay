<?php

namespace App\Repository\Storage\Report;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;

class EventActivityReportRow
{
    public function __construct(
        public readonly string $activity,
        public readonly string $type,
        public readonly int    $totalStock,
        public readonly int    $usedStock,
        public readonly Activity|null $component
    ) {}

    public static function fromActivityInventory(ActivityInventory $inventory): self
    {
        return new self(
            activity: $inventory->activity->name,
            type: $inventory->activity->activityType->name,
            totalStock: $inventory->repository->getTotalStock(),
            usedStock: $inventory->repository->getUsedStock(),
            component: $inventory->activity
        );
    }
}
