<?php

namespace App\Repository\Storage\Rooming;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use Carbon\Carbon;

class AccommodationByDate
{
    /** @var array<int, AccommodationInventory> */
    public array $inventory;

    public function __construct(
        public Accommodation $accommodation,
        public RoomType $room,
        public BoardType $board,
        public RoomCategory|null $category,
    )
    {
        $this->inventory = [];
    }

    public static function createFromInventory(AccommodationInventory $inventory): self
    {
        $instance = new self($inventory->accommodation, $inventory->roomType, $inventory->boardType, $inventory->category);
        $instance->addRoom($inventory);
        return $instance;
    }

    public function getInventoryOnNight(Carbon $night): AccommodationInventory|null
    {
        $night = $night->setTime(0, 0, 0);
        foreach ($this->inventory as $inventory) {
            if ($inventory->check_in->isBefore($night) && $inventory->check_out->isAfter($night)) {
                return $inventory;
            }
        }
        return null;
    }

    public function addRoom(AccommodationInventory $inventory): bool
    {
        if ($this->matches($inventory)) {
            $this->inventory[$inventory->id] = $inventory;
            return true;
        }
        return false;
    }

    public function matches(AccommodationInventory $inventory): bool
    {
        return $inventory->accommodation_id === $this->accommodation->id
                && $inventory->room_type_id === $this->room->id
                && $inventory->board_type_id === $this->board->id
                && $inventory->room_category_id === $this->category?->id;
    }
}
