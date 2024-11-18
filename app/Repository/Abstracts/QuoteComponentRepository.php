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
    abstract public function getShortDescription(): string;
    abstract public function getQuantity(): int|null;
    abstract public function getItineraryTitle(): string;
    abstract public function getItineraryDescription(): string;
    abstract public function getItineraryAsset(): string;
    abstract public function convertToTourComponent(Tour $tour): InventoryTourRepository;
    abstract public function convertToQuoteSection(): QuoteSection;
    abstract public function priceShown(): bool;

    abstract public function getComponentInternalNotes(): string|null;

    abstract public function getComponentExternalNotes(): string|null;

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
