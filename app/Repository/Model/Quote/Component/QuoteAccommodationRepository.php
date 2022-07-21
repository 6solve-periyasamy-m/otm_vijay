<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteAccommodation;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryTourRepository;

class QuoteAccommodationRepository extends QuoteComponentRepository
{
    private QuoteAccommodation $quoteComponent;

    public function __construct(QuoteAccommodation $quoteComponent)
    {
        $this->quoteComponent = $quoteComponent;
    }

    public function getTourComponentType(): string
    {
        return $this->quoteComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->quoteComponent->tour_sales_price;
    }

    public function getInventory(): ?AccommodationInventoryRepository
    {
        return $this->quoteComponent->inventory->repository;
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
        return $this->getInventory()->__toString();
    }

    public function getPurchasePrice(): float
    {
        return $this->getInventory()->get()->purchase_price;
    }

    public function getComponentType(): string
    {
        return 'accommodation';
    }
}
