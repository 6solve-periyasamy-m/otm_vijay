<?php

namespace App\Repository\Model\Quote\Component;


use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\QuoteSection;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Merchandise\MerchandiseInventoryRepository;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsMerchandise;

class QuoteMerchandiseRepository extends QuoteComponentRepository
{
    use IsMerchandise;

    private QuoteMerchandise $quoteComponent;

    public function __construct(QuoteMerchandise $quoteComponent)
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
        return $this->quoteComponent->tour_sales_price ?? 0.0;
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
        return $this->getInventory()?->__toString();
    }

    public function getPurchasePrice(): ?float
    {
        return $this->getInventory()?->get()->purchase_price ?? 0.0;
    }

    public function getShortDescription(): string
    {
        $inventory = $this->getInventory()?->get();
        $component = $inventory->component;
        $string = $component->name;
        if ($inventory->variant !== null) {
            $string .= ' (' . $inventory->variant->name . ')';
        }
        if ($inventory->size !== null) {
            $string .= ' (' . $inventory->size . ')';
        }
        return $string;
    }

    public function getItineraryTitle(): string
    {
        return $this->getShortDescription();
    }

    public function getItineraryDescription(): string
    {
        $inventory = $this->getInventory()?->get();
        $component = $inventory->component;
        return "A {$inventory->variant->name} {$component->name}, in {$inventory->size->name}";
    }

    public function getItineraryAsset(): string
    {
        return $this->getInventory()?->get()->asset;
    }

    public function convertToTourComponent(Tour $tour): InventoryTourRepository
    {
        $tourComponent = MerchandiseInventoryTour::create([
            'tour_id' => $tour->id,
            'tour_component_type' => $this->quoteComponent->tour_component_type,
            'tour_sales_price' => $this->quoteComponent->tour_sales_price,
            'merchandise_inventory_id' => $this->quoteComponent->merchandise_inventory_id,
        ]);
        return $tourComponent->repository;
    }

    public function priceShown(): bool
    {
        return $this->quoteComponent->price_shown ?? false;
    }

    public function getSalesPrice(): float
    {
        return $this->quoteComponent->tour_sales_price ?? 0.0;
    }

    public function convertToQuoteSection(): QuoteSection
    {
        return QuoteSection::create([
            'title' => $this->quoteComponent->inventory->component->name,
            'body' => $this->quoteComponent->inventory->component->type,
            'image_url' => $this->quoteComponent->inventory->component->image_url,
            'quote_id' => $this->quoteComponent->quote_id,
        ]);
    }

    public static function find($id): QuoteMerchandise|null
    {
        return QuoteMerchandise::find($id);
    }

    public function getItineraryItem(int $travelling = 1): ItineraryItem
    {
        return $this->getInventory()?->getItineraryItem($this->quoteComponent->quantity ?? $travelling);
    }
}
