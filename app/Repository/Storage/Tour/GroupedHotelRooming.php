<?php

namespace App\Repository\Storage\Tour;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use App\Models\Booking\BookingGroup;
use App\Models\Booking\BookingTraveller;
use Carbon\Carbon;

class GroupedHotelRooming
{
    public Accommodation $hotel;
    public RoomType $occupancy;
    public BoardType $board;
    public RoomCategory|null $category;
    /** @var AccommodationInventoryTour[] */
    public array $rooms;

    private function __construct(Accommodation $hotel, RoomType $occupancy, BoardType $board, RoomCategory|null $category)
    {
        $this->hotel = $hotel;
        $this->occupancy = $occupancy;
        $this->board = $board;
        $this->category = $category;
        $this->rooms = [];
    }

    /**
     * Build a group from an inventory
     *
     * @param AccommodationInventory $inventory
     * @return GroupedHotelRooming
     */
    public static function fromInventory(AccommodationInventory $inventory): GroupedHotelRooming
    {
        return new GroupedHotelRooming($inventory->accommodation, $inventory->roomType, $inventory->board, $inventory->category);
    }

    /**
     * Build a group from an AccommodationInventoryTour
     *
     * @param AccommodationInventoryTour $inventoryTour
     * @return GroupedHotelRooming
     */
    public static function fromInventoryTour(AccommodationInventoryTour $inventoryTour): GroupedHotelRooming
    {
        $self = static::fromInventory($inventoryTour->inventory);
        $self->add($inventoryTour);
        return $self;
    }

    /**
     * Verify that a room matches this grouping
     *
     * @param AccommodationInventory|AccommodationInventoryTour $inventory
     * @return bool
     */
    public function verify(AccommodationInventory|AccommodationInventoryTour $inventory): bool
    {
        if ($inventory instanceof AccommodationInventoryTour) {
            $inventory = $inventory->inventory;
        }
        return $inventory->accommodation_id === $this->hotel->id &&
                $inventory->room_type_id === $this->occupancy->id &&
                $inventory->board_type_id === $this->board->id &&
                $inventory->room_category_id === $this->category?->id;
    }

    /**
     * Get cost of rooms as an upgrade between two dates
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return float Total cost of upgrades, will return 0.0 if the rooms are included
     */
    public function getUpgradeCost(Carbon|null $start = null, Carbon|null $end = null): float
    {
        $cost = 0;
        foreach ($this->roomsBetweenDates($start, $end) as $room) {
            if ($room->tour_component_type === 'Included') { continue; }
            $cost += $room->tour_sales_price;
        }
        return $cost;
    }

    /**
     * Get included rooms between two dates. Assumes no bound for either end if null
     *
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @return AccommodationInventoryTour[] List of rooms between dates
     */
    public function roomsBetweenDates(Carbon|null $start, Carbon|null $end): array
    {
        if ($start === null && $end === null) { return $this->rooms; }
        $this->sortRooms();
        $start = $start->setTime(0,0,0);
        $end = $end->setTime(23,59,59);
        $array = [];
        foreach ($this->rooms as $room) {
            if ((($start === null) || $room->inventory->check_in->gte($start)) &&
                (($end === null) && $room->inventory->check_out->lte($end)))
            {
                $array[] = $room;
            }
        }
        return $array;
    }

    /**
     * Add a new room to a grouping, if it matches
     *
     * @param AccommodationInventoryTour $inventoryTour Inventory tour to add
     * @return bool true if added, false if not matching
     */
    public function add(AccommodationInventoryTour $inventoryTour): bool
    {
        if (!$this->verify($inventoryTour)) { return false; }
        $this->rooms[] = $inventoryTour;
        $this->sortRooms();
        return true;
    }

    private function sortRooms(): void
    {
        uasort($this->rooms, static function (AccommodationInventoryTour $a, AccommodationInventoryTour $b) { return $a->compare($b); });
    }

    /**
     * Add the rooms between two dates to a specific BookingGroup
     *
     * @param BookingGroup|BookingTraveller $group Group to add to. Will pick travellers first group if traveller provided
     * @param Carbon $start Start date for rooms
     * @param Carbon $end End date for rooms
     * @param bool $overwrite default: true. Should current rooming be overwritten
     * @return void
     */
    public function addToBookingGroup(BookingGroup|BookingTraveller $group, Carbon $start, Carbon $end, bool $overwrite = true): void
    {
        if ($group instanceof BookingTraveller) {
            $group = $group->groups()->first();
        }
        if ($overwrite) {
            $group->accommodation()->forceDelete();
        }
        foreach ($this->roomsBetweenDates($start, $end) as $room) {
            $group->repository->addRoomToGroup($room);
        }
    }
}