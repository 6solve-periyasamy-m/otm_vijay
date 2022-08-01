<?php

namespace App\Repository\Model\Booking\Component;

use App\Models\Booking\Component\BookingTransport;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Model\Transport\TransportInventoryTourRepository;
use App\Repository\Traits\Component\IsTransport;

class BookingTransportRepository extends BookingComponentRepository
{
    use IsTransport;

    private BookingTransport $bookingComponent;

    public function __construct(BookingTransport $bookingComponent)
    {
        $this->bookingComponent = $bookingComponent;
    }

    public function update(array $data): BookingTransport
    {
        $this->bookingComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->bookingComponent->save();
    }

    public function get(): BookingTransport
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

    public function getTourComponent(): TransportInventoryTourRepository
    {
        return $this->bookingComponent->tourComponent->repository;
    }
}
