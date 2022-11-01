<?php

namespace App\Repository\Abstracts;

use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Quote\QuoteSection;
use App\Models\Tour\Tour;

abstract class QuoteComponentRepository extends InventoryContainerRepository
{
    public abstract function getShortDescription(): string;
    public abstract function getItineraryTitle(): string;
    public abstract function getItineraryDescription(): string;
    public abstract function getItineraryAsset(): string;
    public abstract function convertToTourComponent(Tour $tour): InventoryTourRepository;
    public abstract function convertToQuoteSection(): QuoteSection;
    public abstract function priceShown(): bool;
    public abstract function getSalesPrice(): float;

    public function getEditUrl(): string
    {
        return route('quotes.components.edit', ['type' => $this->getComponentType(), 'id' => (int)$this->get()->id, 'quote' => $this->get()->quote]);
    }

    public function getUpdateUrl(): string
    {
        return route('quotes.components.update', ['type' => $this->getComponentType(), 'id' => (int)$this->get()->id, 'quote' => $this->get()->quote]);
    }

    public static function getComponent(string $type, int $id): ?QuoteComponentRepository
    {
        return match ($type) {
            'accommodation' => QuoteAccommodation::find($id)?->repository,
            'activity' => QuoteActivity::find($id)?->repository,
            'flight' => QuoteFlight::find($id)?->repository,
            'transport' => QuoteTransport::find($id)?->repository,
            'merchandise' => QuoteMerchandise::find($id)?->repository,
            default => null,
        };
    }

    public function getConvertUrl(): string
    {
        return route('quotes.components.convert', ['quote' => $this->get()->quote_id, 'type' => $this->getComponentType(), 'id' => $this->get()->id,]);
    }
}
