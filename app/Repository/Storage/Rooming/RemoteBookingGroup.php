<?php

namespace App\Repository\Storage\Rooming;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingGroup;
use App\Models\Booking\BookingTraveller;
use App\Models\Order\OrderCustomer;

class RemoteBookingGroup
{
    /**
     * @var BookingTraveller[]
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
            $this->customers[] = BookingTraveller::find($customerId);
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

    public function convertToGroup(Booking $booking): BookingGroup
    {
        $group = BookingGroup::create(['booking_id' => $booking->id,]);
        foreach ($this->customers as $customer) {
            $group->repository->addTravellerToGroup($customer);
        }
        foreach ($this->rooms as $room) {
            $group->repository->addRoomToGroup($room);
        }
        return $group;
    }
}
