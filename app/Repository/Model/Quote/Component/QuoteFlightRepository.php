<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteFlight;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryTourRepository;
use App\Repository\Model\Activity\ActivityInventoryTourRepository;
use App\Repository\Model\Flight\FlightInventoryTourRepository;
use Illuminate\Database\Eloquent\Model;

class QuoteFlightRepository extends QuoteComponentRepository
{
    private QuoteFlight $quoteComponent;

    public function __construct(QuoteFlight $quoteComponent)
    {
        $this->quoteComponent = $quoteComponent;
    }

    public function getTourComponentType(): string
    {
        return $this->quoteComponent->tourComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->quoteComponent->cost;
    }

    public function getTourComponent(): ?FlightInventoryTourRepository
    {
        return $this->quoteComponent->tourComponent->repository;
    }

    public function get(): QuoteFlight
    {
        return $this->quoteComponent;
    }

    public function update(array $data): QuoteFlight
    {
        $this->quoteComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->quoteComponent->save();
    }

    public function delete(): bool
    {
        return $this->quoteComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->quoteComponent->trashed();
    }

    public function __toString(): string
    {
        return $this->getTourComponent()->__toString();
    }
}
