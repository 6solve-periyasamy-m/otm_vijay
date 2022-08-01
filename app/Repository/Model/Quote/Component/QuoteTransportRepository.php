<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteTransport;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Transport\TransportInventoryRepository;
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
        return $this->quoteComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->quoteComponent->tour_sales_price;
    }

    public function getInventory(): ?TransportInventoryRepository
    {
        return $this->quoteComponent->inventory->repository;
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
        return $this->getInventory()->__toString();
    }

    public function getComponentType(): string
    {
        return 'transport';
    }

    public function getShortDescription(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$inventory->travelClass})";
    }

    public function getItineraryTitle(): string
    {
        return $this->getShortDescription();
    }

    public function getItineraryDescription(): string
    {
        return $this->getInventory()->get()->component->description;
    }

    public function getItineraryAsset(): string
    {
        return asset($this->getInventory()->get()->component->image_url);
    }
}
