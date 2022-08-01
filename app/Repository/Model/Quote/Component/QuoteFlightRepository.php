<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteFlight;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Flight\FlightInventoryRepository;
use App\Repository\Model\Flight\FlightInventoryTourRepository;
use App\Repository\Traits\Component\IsFlight;

class QuoteFlightRepository extends QuoteComponentRepository
{
    use IsFlight;

    private QuoteFlight $quoteComponent;

    public function __construct(QuoteFlight $quoteComponent)
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

    public function getInventory(): ?FlightInventoryRepository
    {
        return $this->quoteComponent->inventory->repository;
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
        return $this->getInventory()->__toString();
    }

    public function getShortDescription(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->departureAirport->name} to {$component->arrivalAirport->name} ({$inventory->travelClass})";
    }

    public function getItineraryTitle(): string
    {
        return $this->getShortDescription();
    }

    public function getItineraryDescription(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "A flight from {$component->departureAirport->name} to {$component->arrivalAirport->name}, with {$inventory->travelClass} seating.";
    }

    public function getItineraryAsset(): string
    {
        return asset($this->getInventory()->get()->component->image_url);
    }
}
