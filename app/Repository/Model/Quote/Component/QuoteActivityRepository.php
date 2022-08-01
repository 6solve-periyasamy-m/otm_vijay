<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteActivity;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Activity\ActivityInventoryRepository;
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
        return $this->quoteComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->quoteComponent->cost;
    }

    public function getInventory(): ?ActivityInventoryRepository
    {
        return $this->quoteComponent->inventory->repository;
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
        return $this->getInventory()->__toString();
    }

    public function getComponentType(): string
    {
        return 'activity';
    }

    public function getShortDescription(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$component->activityType}) ({$inventory->ticketType})";
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
