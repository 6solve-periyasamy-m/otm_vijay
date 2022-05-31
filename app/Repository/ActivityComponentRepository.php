<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ActivityComponentRepository
{
    public static function isOnUpgradeTree(ActivityInventoryTour $inventoryTour, ActivityInventoryTourUpgrade $upgrade): bool
    {
        if ($upgrade->base_id == $inventoryTour->id) return true;
        foreach ($inventoryTour->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null): Collection
    {
        $inventories = [];
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return ActivityInventory::whereBetween('starts_at', [$dateFrom, $dateTo])->whereBetween('ends_at', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }
}
