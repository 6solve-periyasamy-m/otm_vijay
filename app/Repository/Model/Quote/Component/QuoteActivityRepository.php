<?php

namespace App\Repository\Model\Quote\Component;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteSection;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Activity\ActivityInventoryRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsActivity;

class QuoteActivityRepository extends QuoteComponentRepository
{
    use IsActivity;

    private QuoteActivity $quoteComponent;

    public function __construct(QuoteActivity $quoteComponent)
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

    public function getInventory(): ?ActivityInventoryRepository
    {
        return $this->quoteComponent->inventory?->repository;
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
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$component->activityType}) ({$component->address->region}, {$component->address->country}) - {$inventory->ticketType}";
    }

    public function getShortDescription(): string
    {
        $inventory = $this->getInventory()->get();
        $component = $inventory->component;
        return "{$component->name} ({$component->activityType}) ({$inventory->ticketType})";
    }

    public function getActivityData()
    {
        $inventory = $this->getInventory()->get();
        return $component = $inventory->component;
        // return "{$component->name} ({$component->activityType}) ({$inventory->ticketType})";
        
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
        $tourComponent = ActivityInventoryTour::create([
            'tour_id' => $tour->id,
            'tour_component_type' => $this->quoteComponent->tour_component_type,
            'tour_sales_price' => $this->quoteComponent->tour_sales_price,
            'activity_inventory_id' => $this->quoteComponent->activity_inventory_id,
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

    public static function find($id): QuoteActivity|null
    {
        return QuoteActivity::find($id);
    }

    public function getItineraryItem(int $travelling = 1, Quote|null $quote = null): ItineraryItem
    {
        $item = $this->getInventory()?->getItineraryItem($this->quoteComponent->quantity ?? $travelling);

        $event = is_array($quote->event) ? new Event($quote->event) : $quote->event;
        $item->name =  $event?->name;
        return $item;
    }
}
