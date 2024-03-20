<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Order;
use App\Repository\Storage\Rooming\RemoteGroup;

class OrderRoomingRepository
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getRoomingData(): array
    {
        $rooms = [];
        foreach ($this->order->tour->accommodationInventoryTours()->with('inventory', 'inventory.component')->get() as $inventoryTour) {
            $rooms[$inventoryTour->id] = [
                'name' => $inventoryTour->repository->formatAdminOccupancy(),
                'size' => $inventoryTour->inventory->roomType->maximum_occupancy,
                'price' => $inventoryTour->tour_component_type === 'Included' ? 0 : $inventoryTour->tour_sales_price,
                'start' => $inventoryTour->inventory->check_in->unix(),
                'end' => $inventoryTour->inventory->check_out->unix(),
            ];
        }
        $customers = [];
        foreach ($this->order->orderCustomers()->with('customer')->get() as $orderCustomer) {
            if (!$orderCustomer->is_travelling) continue;
            $customers[$orderCustomer->id] = ['name' => $orderCustomer->customer_name, 'avatar' => $orderCustomer->customer->avatar_url,];
        }
        $groups = [];
        foreach ($this->order->groups as $group) {
            $groupCustomers = [];
            foreach ($group->orderCustomers as $orderCustomer) {
                if (!$orderCustomer->is_travelling) continue;
                $groupCustomers[] = $orderCustomer->id;
            }
            $groupRooms = [];
            foreach ($group->rooms as $room) {
                $groupRooms[] = $room->accommodation_inventory_tour_id;
            }
            $groups[$group->id] = ['rooms' => $groupRooms, 'customers' => $groupCustomers,];
        }
        return ['rooms' => $rooms, 'customers' => $customers, 'groups' => $groups,];
    }

    /**
     * @param RemoteGroup[] $remoteGroups
     * @return void
     */
    public function importRoomingData(array $remoteGroups): void
    {
        $this->wipeGroups();
        foreach ($remoteGroups as $remoteGroup) {
            $remoteGroup->convertToGroup();
        }
    }

    private function wipeGroups(): void
    {
        foreach ($this->order->groups as $group) {
            $group->rooms()->delete();
            $group->pivot()->delete();
            $group->delete();
        }
    }
}
