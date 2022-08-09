<?php

namespace App\Repository\Model\Booking\Component;

use App\Models\Booking\Component\BookingActivity;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Model\Activity\ActivityInventoryTourRepository;
use App\Repository\Traits\Component\IsActivity;

class BookingActivityRepository extends BookingComponentRepository
{
    use IsActivity;

    private BookingActivity $bookingComponent;

    public function __construct(BookingActivity $bookingComponent)
    {
        $this->bookingComponent = $bookingComponent;
    }

    public function update(array $data): BookingActivity
    {
        $this->bookingComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->bookingComponent->save();
    }

    public function get(): BookingActivity
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

    public function getTourComponent(): ActivityInventoryTourRepository
    {
        return $this->bookingComponent->tourComponent->repository;
    }
}
