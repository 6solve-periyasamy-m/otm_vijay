<?php

namespace Tests\Traits\Model;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
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
        return AccommodationInventory::factory()->create(['accommodation_id' => $accommodation, 'room_type_id' => $roomType->id, 'board_type_id' => $boardType->id, ...$attributes]);
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
