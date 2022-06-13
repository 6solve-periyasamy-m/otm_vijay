<?php

namespace App\Repository;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Customer\Group;
use App\Models\Customer\OrderCustomerGroup;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use DB;
use Illuminate\Support\Collection;

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

    public function addRoomToGroup(AccommodationInventoryTour $tourComponent): OrderAccommodation
    {
        $exists = OrderAccommodation::where('group_id', '=', $this->group->id)->where('accommodation_inventory_tour_id', '=', $tourComponent->id)->first();
        if ($exists) return $exists;
        $oAccom = OrderAccommodation::make(['accommodation_inventory_tour_id' => $tourComponent->id, 'cost' => $tourComponent->tour_sales_price,]);
        $this->group->rooms()->save($oAccom);
        return $oAccom;
    }
}
