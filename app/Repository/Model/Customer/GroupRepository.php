<?php

namespace App\Repository\Model\Customer;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Customer\Group;
use App\Models\Customer\OrderCustomerGroup;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use App\Repository\RoomingRepository;
use DB;

class GroupRepository
{
    private Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function getOrderCustomers(): array
    {
        $query = DB::table('order_customer_group');
        $query->where('group_id', '=', $this->group->id);
        $query->select('order_customer_id');
        $orderCustomers = [];
        foreach ($query->get() as $result) {
            $orderCustomers[] = OrderCustomer::find($result->order_customer_id);
        }
        return $orderCustomers;
    }

    public function addCustomerToGroup(OrderCustomer $orderCustomer): OrderCustomerGroup
    {
        $exists = OrderCustomerGroup::where('group_id', '=', $this->group->id)->where('order_customer_id', '=', $orderCustomer->id)->first();
        if (isset($exists)) return $exists;
        return OrderCustomerGroup::create(['group_id' => $this->group->id, 'order_customer_id' => $orderCustomer->id,]);
    }

    public function removeCustomerFromGroup(OrderCustomer $orderCustomer): bool
    {
        $exists = OrderCustomerGroup::where('group_id', '=', $this->group->id)->where('order_customer_id', '=', $orderCustomer->id)->first();
        if (!isset($exists)) return false;
        $exists->delete();
        return true;
    }

    public function addRoomToGroup(AccommodationInventoryTour $tourComponent, bool $silent = false): OrderAccommodation
    {
        $exists = OrderAccommodation::where('group_id', '=', $this->group->id)->where('accommodation_inventory_tour_id', '=', $tourComponent->id)->first();
        if ($exists) return $exists;
        $orderAccommodation = OrderAccommodation::make([
            'accommodation_inventory_tour_id' => $tourComponent->id,
            'cost' => $tourComponent->tour_sales_price,
            'estimated_purchase_price' => $tourComponent->inventory->local_purchase_price,
        ]);
        $silent ? $this->group->rooms()->saveQuietly($orderAccommodation) : $this->group->rooms()->save($orderAccommodation);
        return $orderAccommodation;
    }

    public function getAdditionalCosts(): array
    {
        $upgrades = [];
        $addons = [];
        $additionalValue = 0;
        foreach ($this->group->rooms as $orderAccommodation) {
            if ($orderAccommodation->tourComponent->tour_component_type == 'Upgrade') {
                $upgrades[] = ['upgrade' => $orderAccommodation, 'description' => "{$orderAccommodation->tourComponent}  ({$this->group->getMembers()})"];
                $additionalValue += $orderAccommodation->cost;
            }
            if ($orderAccommodation->tourComponent->tour_component_type == 'Add-on') {
                $addons[] = ['addon' => $orderAccommodation, 'description' => "{$orderAccommodation->tourComponent}  ({$this->group->getMembers()})",];
                $additionalValue += $orderAccommodation->cost;
            }
        }
        return ['addons' => $addons, 'upgrades' => $upgrades, 'additionalValue' => $additionalValue,];
    }

    public function refreshRooming(): void
    {
        $this->group->rooms()->delete();
        $order = $this->group->orderCustomers()->first()->order;
        $templates = RoomingRepository::getTemplateTourInventory($order->tour);
        foreach ($templates as $template) {
            $room = RoomingRepository::getInventoryWithRoomType($template, $this->group->roomType);
            $this->addRoomToGroup($room);
        }
    }

    public function forceDelete(): void
    {
        $this->group->rooms()->forceDelete();
        $this->group->pivot()->forceDelete();
        $this->group->forceDelete();
    }
}
