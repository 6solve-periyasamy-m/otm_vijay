<?php

namespace App\Repository\Abstracts;

use App\Models\Tour\Tour;
use App\Repository\Interfaces\HasStockControl;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class InventoryRepository extends ModelRepository implements HasStockControl
{
    public abstract function getStartTime(): Carbon;

    public abstract function getEndTime(): Carbon;

    public static abstract function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection;
}
