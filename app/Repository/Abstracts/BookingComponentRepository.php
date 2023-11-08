<?php

namespace App\Repository\Abstracts;

use App\Models\Booking\Component\BookingAccommodation;
use App\Models\Booking\Component\BookingActivity;
use App\Models\Booking\Component\BookingFlight;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Booking\Component\BookingTransport;
use App\Repository\Interfaces\HasComponentType;

abstract class BookingComponentRepository extends ModelRepository implements HasComponentType
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
    public abstract function getTourComponent(): InventoryTourRepository;

    public static function getComponent(string $type, int $id): ?BookingComponentRepository
    {
        return match ($type) {
            'accommodation' => BookingAccommodation::find($id)?->repository,
            'activity' => BookingActivity::find($id)?->repository,
            'flight' => BookingFlight::find($id)?->repository,
            'transport' => BookingTransport::find($id)?->repository,
            'merchandise' => BookingMerchandise::find($id)?->repository,
            default => null,
        };
    }
}
