<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Flight\FlightInventoryTour;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\QuoteSection;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Flight\FlightInventoryRepository;
use App\Repository\Traits\Component\IsFlight;

class QuoteFlightRepository extends QuoteComponentRepository
{
    use IsFlight;

    private QuoteFlight $quoteComponent;

    public function __construct(QuoteFlight $quoteComponent)
    {
        $this->quoteComponent = $quoteComponent;
    }

    public function getQuantity(): int|null
    {
        return $this->quoteComponent->quantity;
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
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->departureAirport->name} to {$component->arrivalAirport->name} ({$inventory->travelClass})";
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

    public function convertToTourComponent(Tour $tour): InventoryTourRepository
    {
        $tourComponent = FlightInventoryTour::create([
            'tour_id' => $tour->id,
            'tour_component_type' => $this->quoteComponent->tour_component_type,
            'tour_sales_price' => $this->quoteComponent->tour_sales_price,
            'flight_inventory_id' => $this->quoteComponent->flight_inventory_id,
            'flight_type' => $this->quoteComponent->flight_type,
        ]);
        return $tourComponent->repository;
    }

    public function priceShown(): bool
    {
        return $this->quoteComponent->price_shown ?? false;
    }

    public function getSalesPrice(): float
    {
        return $this->quoteComponent->tour_sales_price ?? 0;
    }

    public function convertToQuoteSection(): QuoteSection
    {
        return QuoteSection::create([
            'title' => $this->quoteComponent->inventory->component->departureAirport . ' to ' . $this->quoteComponent->inventory->component->arrivalAirport,
            'body' => $this->quoteComponent->inventory->travelClass,
            'image_url' => $this->quoteComponent->inventory->component->image_url,
            'quote_id' => $this->quoteComponent->quote_id,
        ]);
    }

    public static function find($id): QuoteFlight|null
    {
        return QuoteFlight::find($id);
    }
}
