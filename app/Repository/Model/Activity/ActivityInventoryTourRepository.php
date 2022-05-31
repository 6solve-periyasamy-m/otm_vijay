<?php

namespace App\Repository\Model\Activity;

use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use Illuminate\Database\Eloquent\Model;

class ActivityInventoryTourRepository extends InventoryTourRepository
{
    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->activityInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->activityInventory->activity->name;
                $components[$component->id]['activity_type'] = $component->activityInventory->activity->activityType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderActivities as $oComponent) {
                $component = $oComponent->activityInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        // TODO: Implement grantToCustomer() method.
    }

    public function getUpgradeParent(): Model
    {
        // TODO: Implement getUpgradeParent() method.
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        // TODO: Implement onUpgradeTree() method.
    }

    public function get(): Model
    {
        // TODO: Implement get() method.
    }

    public function update(array $data): Model
    {
        // TODO: Implement update() method.
    }

    public function save(): bool
    {
        // TODO: Implement save() method.
    }

    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }

    public function isDeleted(): bool
    {
        // TODO: Implement isDeleted() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}