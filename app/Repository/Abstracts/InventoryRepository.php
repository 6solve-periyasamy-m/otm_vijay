<?php

namespace App\Repository\Abstracts;

use App\Models\Tour\Tour;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class InventoryRepository extends ModelRepository
{
    public abstract function getStartTime(): Carbon;

    public abstract function getEndTime(): Carbon;

    public static abstract function getBetweenDates(Carbon $from, Carbon $to, Tour $tour = null): Collection;
}