<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteMerchandise;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Tour\MerchandiseRepository;

class QuoteMerchandiseRepository extends QuoteComponentRepository
{
    private QuoteMerchandise $quoteComponent;

    public function __construct(QuoteMerchandise $quoteComponent)
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

    public function getTourComponent(): ?MerchandiseRepository
    {
        return $this->quoteComponent->tourComponent->repository;
    }

    public function get(): QuoteMerchandise
    {
        return $this->quoteComponent;
    }

    public function update(array $data): QuoteMerchandise
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
        return $this->getTourComponent()->get()->purchase_price;
    }
}
