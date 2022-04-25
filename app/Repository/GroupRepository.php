<?php

namespace App\Repository;

use App\Models\AccommodationInventoryTour;
use App\Models\Group;
use App\Models\OrderAccommodation;
use App\Models\OrderCustomer;
use App\Models\OrderCustomerGroup;
use Illuminate\Support\Collection;
use DB;
use Log;

class GroupRepository
{
    private Group $group;

    public function __construct(Group $group) {
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
        return OrderCustomerGroup::create(['group_id' => $this->group->id, 'order_customer_id' => $orderCustomer->id, ]);
    }

    public function removeCustomerFromGroup(OrderCustomer $orderCustomer): bool
    {
        $exists = OrderCustomerGroup::where('group_id', '=', $this->group->id)->where('order_customer_id', '=', $orderCustomer->id)->first();
        if (!isset($exists)) return false;
        $exists->delete();
        return true;
    }

    public function addRoomToGroup(AccommodationInventoryTour $tourComponent): OrderAccommodation
    {
        $exists = OrderAccommodation::where('group_id', '=', $this->group->id)->where('accommodation_inventory_tour_id', '=', $tourComponent->id)->first();
        if ($exists) return $exists;
        $oAccom = OrderAccommodation::make(['accommodation_inventory_tour_id' => $tourComponent->id, 'cost' => $tourComponent->tour_sales_price,]);
        $this->group->rooms()->save($oAccom);
        return $oAccom;
    }

    public static function getGroups(OrderCustomer $orderCustomer): array
    {
        $query = DB::table('order_customer_group');
        $query->where('order_customer_id', '=', $orderCustomer->id);
        $query->whereNull('deleted_at');
        $query->select('group_id');
        $groups = [];
        foreach ($query->get() as $result) {
            $groups[] = Group::find($result->group_id);
        }
        return $groups;
    }

    public static function getOrderCustomerAccommodation(OrderCustomer $orderCustomer): Collection
    {
        $accommodation = new Collection();
        foreach (self::getGroups($orderCustomer) as $group) {
            if (!isset($group)) continue;
            $accommodation = $accommodation->merge($group->rooms);
        }
        return $accommodation;
    }
}
