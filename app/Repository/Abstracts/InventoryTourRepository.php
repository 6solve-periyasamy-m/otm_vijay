<?php

namespace App\Repository\Abstracts;

use App\Models\Booking\BookingTraveller;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Illuminate\Database\Eloquent\Model;

abstract class InventoryTourRepository extends ModelRepository
{
    public static abstract function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array;

    public abstract function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository;

    public abstract function grantToTraveller(BookingTraveller $traveller): ?BookingComponentRepository;

    public abstract function getUpgradeParent(): Model;

    public abstract function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool;
}