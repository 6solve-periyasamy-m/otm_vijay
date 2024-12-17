<?php

namespace App\Repository\Model\Booking\Component;

use App\Models\Booking\Component\BookingAccommodation;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryTourRepository;
use App\Repository\Traits\Component\IsAccommodation;

class BookingAccommodationRepository extends BookingComponentRepository
{
    use IsAccommodation;

    private BookingAccommodation $bookingComponent;

    public function __construct(BookingAccommodation $bookingComponent)
    {
        $this->bookingComponent = $bookingComponent;
    }

    public function update(array $data): BookingAccommodation
    {
        $this->bookingComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->bookingComponent->save();
    }

    public function get(): BookingAccommodation
    {
        return $this->bookingComponent;
    }

    public function delete(): bool
    {
        return $this->bookingComponent->delete();
    }

    public function isDeleted(): bool
    {
        return !isset($this->bookingComponent);
    }

    public function __toString(): string
    {
        return "{$this->bookingComponent->tourComponent}";
    }

    public function getTourComponentType(): string
    {
        return $this->bookingComponent->tourComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->bookingComponent->tourComponent->tour_sales_price ?? 0.0;
    }

    public function getTourComponent(): AccommodationInventoryTourRepository
    {
        return $this->bookingComponent->tourComponent->repository;
    }

    public static function find($id): BookingAccommodation|null
    {
        return BookingAccommodation::find($id);
    }
}
