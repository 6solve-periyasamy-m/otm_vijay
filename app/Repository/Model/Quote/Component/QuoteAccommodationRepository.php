<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\QuoteSection;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use App\Repository\Traits\Component\IsAccommodation;

class QuoteAccommodationRepository extends QuoteComponentRepository
{
    use IsAccommodation;

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
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$component->address->region}, {$component->address->country}) - {$inventory->roomType} {$inventory->boardType}";
    }
    public function getHotelAddress(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return $component->address;
    }
    public function getRoomType(): string
    {
        $inventory = $this->getInventory()->get();
       
        return $inventory->boardType;
    }
    public function getHotelName(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name}";
    }
    public function getShortDescription(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$inventory->boardType})";
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
        $tourComponent = AccommodationInventoryTour::create([
            'tour_id' => $tour->id,
            'tour_component_type' => $this->quoteComponent->tour_component_type,
            'tour_sales_price' => $this->quoteComponent->tour_sales_price,
            'accommodation_inventory_id' => $this->quoteComponent->accommodation_inventory_id,
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

    public static function find($id): QuoteAccommodation|null
    {
        return QuoteAccommodation::find($id);
    }
}
