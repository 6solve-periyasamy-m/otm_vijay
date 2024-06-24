<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Quote\Component\QuoteTransport;
use App\Models\Quote\QuoteSection;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Transport\TransportInventoryRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsTransport;

class QuoteTransportRepository extends QuoteComponentRepository
{
    use IsTransport;

    private QuoteTransport $quoteComponent;

    public function __construct(QuoteTransport $quoteComponent)
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
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$component->departureAddress->region}, {$component->departureAddress->country} to {$component->arrivalAddress->region}, {$component->arrivalAddress->country}) ({$inventory->travelClass})";
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

    public function convertToTourComponent(Tour $tour): InventoryTourRepository
    {
        $tourComponent = TransportInventoryTour::create([
            'tour_id' => $tour->id,
            'tour_component_type' => $this->quoteComponent->tour_component_type,
            'tour_sales_price' => $this->quoteComponent->tour_sales_price,
            'transport_inventory_id' => $this->quoteComponent->transport_inventory_id,
        ]);
        return $tourComponent->repository;
    }

    public function convertToQuoteSection(): QuoteSection
    {
        return QuoteSection::create([
            'title' => $this->quoteComponent->inventory->component->name,
            'body' => $this->quoteComponent->inventory->component->description,
            'image_url' => $this->quoteComponent->inventory->component->image_url,
            'quote_id' => $this->quoteComponent->quote_id,
        ]);
    }

    public function priceShown(): bool
    {
        return $this->quoteComponent->price_shown ?? false;
    }

    public function getSalesPrice(): float
    {
        return $this->quoteComponent->tour_sales_price ?? 0;
    }

    public static function find($id): QuoteTransport|null
    {
        return QuoteTransport::find($id);
    }

    public function getItineraryItem(int $travelling = 1): ItineraryItem
    {
        $item = $this->quoteComponent->inventory->repository->getItineraryItem();
        $item->quantity = $this->quoteComponent->quantity ?? $travelling;
        return $item;
    }
}
