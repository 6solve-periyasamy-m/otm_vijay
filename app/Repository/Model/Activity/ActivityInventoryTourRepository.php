<?php

namespace App\Repository\Model\Activity;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Model\Order\Component\OrderActivityRepository;
use Illuminate\Database\Eloquent\Model;

class ActivityInventoryTourRepository extends InventoryTourRepository
{
    private ActivityInventoryTour $tourComponent;

    public function __construct(ActivityInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

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

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderActivityRepository
    {
        $orderComponent = OrderActivity::create([
            'order_customer_id' => $orderCustomer->id,
            'activity_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent;
    }

    public function getUpgradeParent(): ActivityInventoryTour
    {
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        // TODO: Implement onUpgradeTree() method.
    }

    public function get(): ActivityInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): ActivityInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->tourComponent->save();
    }

    public function delete(): bool
    {
        return $this->tourComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->tourComponent->trashed();
    }

    public function __toString(): string
    {
        $inventory = $this->tourComponent->activityInventory;
        $component = $inventory->activity;
        return $component->name . ' (' . f_datetime($inventory->starts_at) . ' to ' . f_datetime($inventory->ends_at) . ') (' . $inventory->ticketType->name . ')';
    }
}