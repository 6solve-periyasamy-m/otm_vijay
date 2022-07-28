<?php

namespace App\Repository\Abstracts;

use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use Carbon\Carbon;

abstract class QuoteComponentRepository extends ModelRepository
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
    public abstract function getPurchasePrice(): ?float;
    public abstract function getInventory(): ?InventoryRepository;
    public abstract function getComponentType(): string;
    public abstract function getShortDescription(): string;
    public abstract function getItineraryTitle(): string;
    public abstract function getItineraryDescription(): string;
    public abstract function getItineraryAsset(): string;

    public function getStartTime(): Carbon
    {
        return $this->getInventory()->getStartTime();
    }

    public function getEndTime(): Carbon
    {
        return $this->getInventory()->getEndTime();
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
}
