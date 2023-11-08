<?php

namespace App\Repository\Model\Booking\Component;

use App\Models\Booking\Component\BookingFlight;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Model\Flight\FlightInventoryTourRepository;
use App\Repository\Traits\Component\IsFlight;

class BookingFlightRepository extends BookingComponentRepository
{
    use IsFlight;

    private BookingFlight $bookingComponent;

    public function __construct(BookingFlight $bookingComponent)
    {
        $this->bookingComponent = $bookingComponent;
    }

    public function update(array $data): BookingFlight
    {
        $this->bookingComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->bookingComponent->save();
    }

    public function get(): BookingFlight
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
        return $this->bookingComponent->tourComponent->tour_sales_price;
    }

    public function getTourComponent(): FlightInventoryTourRepository
    {
        return $this->bookingComponent->tourComponent->repository;
    }

    public static function find($id): BookingFlight|null
    {
        return BookingFlight::find($id);
    }
}
