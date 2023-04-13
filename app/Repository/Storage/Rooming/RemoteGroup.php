<?php

namespace App\Repository\Storage\Rooming;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Customer\Group;
use App\Models\Order\OrderCustomer;

class RemoteGroup
{
    /**
     * @var OrderCustomer[]
     */
    private array $customers;
    /**
     * @var AccommodationInventoryTour[]
     */
    private array $rooms;

    /**
     * @param int[] $customers
     * @param int[] $rooms
     */
    public function __construct(array $customers, array $rooms)
    {
        $this->customers = [];
        $this->rooms = [];
        foreach ($customers as $customerId) {
            $this->customers[] = OrderCustomer::find($customerId);
        }
        foreach ($rooms as $roomId) {
            $this->rooms[] = AccommodationInventoryTour::find($roomId);
        }
    }

    /**
     * @return OrderCustomer[]
     */
    public function getCustomers(): array
    {
        return $this->customers;
    }

    /**
     * @return AccommodationInventoryTour[]
     */
    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function convertToGroup(): Group
    {
        $group = Group::create();
        foreach ($this->customers as $customer) {
            $group->repository->addCustomerToGroup($customer);
        }
        foreach ($this->rooms as $room) {
            $group->repository->addRoomToGroup($room);
        }
        return $group;
    }
}
