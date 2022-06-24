<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteAccommodation;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryTourRepository;
use Illuminate\Database\Eloquent\Model;

class QuoteAccommodationRepository extends QuoteComponentRepository
{
    private QuoteAccommodation $quoteComponent;

    public function __construct(QuoteAccommodation $quoteComponent)
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

    public function getTourComponent(): ?AccommodationInventoryTourRepository
    {
        return $this->quoteComponent->tourComponent->repository;
    }

    public function get(): QuoteAccommodation
    {
        return $this->quoteComponent;
    }

    public function update(array $data): QuoteAccommodation
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
