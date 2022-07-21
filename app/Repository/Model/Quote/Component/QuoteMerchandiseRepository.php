<?php

namespace App\Repository\Model\Quote\Component;


use App\Models\Quote\Component\QuoteMerchandise;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Merchandise\MerchandiseInventoryRepository;
use App\Repository\Model\Merchandise\MerchandiseInventoryTourRepository;

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

    public function getInventory(): ?MerchandiseInventoryRepository
    {
        return $this->quoteComponent->inventory->repository;
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
        return $this->getInventory()->__toString();
    }

    public function getPurchasePrice(): float
    {
        return $this->getInventory()->get()->purchase_price;
    }

    public function getComponentType(): string
    {
        return 'merchandise';
    }
}
