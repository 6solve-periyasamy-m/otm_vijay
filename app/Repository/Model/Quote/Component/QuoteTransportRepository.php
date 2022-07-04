<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteTransport;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Transport\TransportInventoryTourRepository;

class QuoteTransportRepository extends QuoteComponentRepository
{
    private QuoteTransport $quoteComponent;

    public function __construct(QuoteTransport $quoteComponent)
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

    public function getTourComponent(): ?TransportInventoryTourRepository
    {
        return $this->quoteComponent->tourComponent->repository;
    }

    public function get(): QuoteTransport
    {
        return $this->quoteComponent;
    }

    public function update(array $data): QuoteTransport
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
