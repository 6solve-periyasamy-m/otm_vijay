<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteActivity;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Activity\ActivityInventoryTourRepository;

class QuoteActivityRepository extends QuoteComponentRepository
{
    private QuoteActivity $quoteComponent;

    public function __construct(QuoteActivity $quoteComponent)
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

    public function getTourComponent(): ?ActivityInventoryTourRepository
    {
        return $this->quoteComponent->tourComponent->repository;
    }

    public function get(): QuoteActivity
    {
        return $this->quoteComponent;
    }

    public function update(array $data): QuoteActivity
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

    public function getPurchasePrice(): float
    {
        return $this->getTourComponent()->getInventory()->get()->purchase_price;
    }
}
