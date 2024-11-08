<?php

namespace Tests\Traits\Model\Prefab;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomType;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Flight\FlightInventory;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Transport\TransportInventory;
use App\Models\TravelClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Tests\Traits\Model\TestsAccommodation;
use Tests\Traits\Model\TestsActivity;
use Tests\Traits\Model\TestsFlight;
use Tests\Traits\Model\TestsMerchandise;
use Tests\Traits\Model\TestsTransport;

trait BuildsInventory
{
    use TestsAccommodation, TestsActivity, TestsFlight, TestsTransport, TestsMerchandise;

    private static array $inventoryPreset = [
        'stock' => 50,
        'purchase_price' => 50,
        'sales_price' => 100,
        'internal_notes' => 'Test Component - Internal Notes',
        'external_notes' => 'Test Component - External Notes',
    ];

    public function verifyPreset(Model $model): void
    {
        foreach (self::$inventoryPreset as $key => $value) {
            $this->assertEquals($value, ($model->$key ?? null));
        }
    }

    public function buildAccommodationInventory(RoomType|int $size, Carbon $start, Carbon $end): AccommodationInventory
    {
        if (is_int($size)) {
            $size = $this->generateRoomType($size);
        }
        return $this->generateAccommodationInventory(
            $this->generateAccommodation(),
            $size,
            $this->generateBoardType(),
            [
                'check_in' => $start,
                'check_out' => $end,
                'check_in_time_confirmed' => true,
                'check_out_time_confirmed' => true,
                ...self::$inventoryPreset,
            ],
        );
    }

    public function buildActivityInventory(Carbon $start, Carbon $end): ActivityInventory
    {
        return $this->generateActivityInventory(
            $this->generateActivity(),
            $this->generateTicketType(),
            [
                'starts_at' => $start,
                'ends_at' => $end,
                ...self::$inventoryPreset,
            ],
        );
    }

    public function buildFlightInventory(Carbon $start, Carbon $end): FlightInventory
    {
        return $this->generateFlightInventory(
            $this->generateFlight(),
            TravelClass::factory()->create(),
            [
                'check_in' => $start,
                'departs_at' => $start,
                'arrives_at' => $end,
                ...self::$inventoryPreset,
            ],
        );
    }

    public function buildTransportInventory(Carbon $start, Carbon $end): TransportInventory
    {
        return $this->generateTransportInventory(
            $this->generateTransport(),
            TravelClass::factory()->create(),
            [
                'departs_at' => $start,
                'arrives_at' => $end,
                ...self::$inventoryPreset,
            ],
        );
    }

    public function buildMerchandiseInventory(): MerchandiseInventory
    {
        return $this->generateMerchandiseInventory(
            $this->generateMerchandise(),
            [
                ...self::$inventoryPreset,
            ],
        );
    }
}
