<?php

namespace App\Repository\Abstracts;

use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Illuminate\Database\Eloquent\Model;

abstract class InventoryTourRepository extends ModelRepository
{
    public static abstract function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array;

    public abstract function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function getUpgradeParent(): Model;

    public abstract function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool;
}