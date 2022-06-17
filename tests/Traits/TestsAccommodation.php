<?php

namespace Tests\Traits;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomType;

trait TestsAccommodation
{
    public function generateAccommodation(): Accommodation
    {
        return Accommodation::factory()->create();
    }

    public function generateAccommodationInventory(Accommodation $accommodation = null, RoomType $roomType = null, BoardType $boardType = null, array $attributes = []): AccommodationInventory
    {
        if (!isset($accommodation)) $accommodation = $this->generateAccommodation();
        if (!isset($roomType)) $roomType = $this->generateRoomType();
        if (!isset($boardType)) $boardType = $this->generateBoardType();
        $inventory = AccommodationInventory::factory()->makeOne($attributes);
        $inventory->room_type_id = $roomType->id;
        $inventory->board_type_id = $boardType->id;
        $accommodation->inventory()->save($inventory);
        return $inventory;
    }

    public function generateRoomType(int $size = 1): RoomType
    {
        return RoomType::factory()->create(['maximum_occupancy' => $size,]);
    }

    public function generateBoardType(): BoardType
    {
        return BoardType::factory()->create();
    }
}
