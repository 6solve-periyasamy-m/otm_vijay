<?php

namespace App\Repository\Abstracts;

use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class InventoryTourRepository extends ModelRepository
{
    public static abstract function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array;

    public static abstract function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null): Collection;

    public abstract function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function getUpgradeParent(): Model;

    public abstract function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool;
}