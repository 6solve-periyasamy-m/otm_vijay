<?php

namespace App\Repository\Storage\Customer\Component;

use App\Models\Booking\BookingTraveller;
use App\Repository\Abstracts\InventoryTourRepository;
use Livewire\Wireable;


/**
 * @property-read bool $owned
 */
class BookingComponent extends AbstractComponent implements Wireable
{
    public readonly BookingTraveller $traveller;
    /**
     * @var BookingComponent[] $upgrades
     */
    private array $upgrades;

    public function __construct(InventoryTourRepository $component, BookingTraveller $traveller)
    {
        parent::__construct($component);
        $this->traveller = $traveller;
        $this->owned = $component->getBookingComponent($traveller) !== null;
    }

    public function getOwnedAttribute(): bool
    {
        return $this->component->getBookingComponent($this->traveller) !== null;
    }

    public function getAvailableUpgrades(): array
    {
        if (!empty($this->upgrades)) return $this->upgrades;
        $this->upgrades = [];
        $parent = $this->component->getUpgradeParent();
        $this->upgrades[] = $parent->repository->getAbstractBookingComponent($this->traveller);
        foreach ($parent->upgrades as $upgrade) {
            $this->upgrades[] = $upgrade->upgrade->repository->getAbstractBookingComponent($this->traveller);
        }
        return $this->upgrades;
    }

    public function canBookForAll(): bool
    {
        return !$this->component->isStockControlActive() || $this->component->hasEnoughStock($this->traveller->booking->travellers()->count());
    }

    public function canBookForOne(): bool
    {
        return !$this->component->isStockControlActive() || $this->component->hasEnoughStock($this->component->getStockUsedOnBooking($this->traveller->booking) + 1);
    }

    public function getAvailableStock(): int
    {
        return $this->component->getAvailableStock();
    }

    public function getUsedStockOnBooking(): int
    {
        return $this->component->getStockUsedOnBooking($this->traveller->booking);
    }

    public function purchaseForOne(): bool
    {
        if ($this->owned) return false;
        if (!$this->canBookForOne()) return false;
        $this->component->grantToBookingTraveller($this->traveller);
        return true;
    }

    public function purchaseForAll(): bool
    {
        if (!$this->canBookForAll()) return false;
        $this->component->bookForAll($this->traveller->booking);
        return true;
    }

    public function sellForOne(): bool
    {
        return $this->component->getBookingComponent($this->traveller)?->delete();
    }

    public function sellForAll(): bool
    {
        return $this->component->unbookForAll($this->traveller->booking);
    }

    public function toLivewire(): array
    {
        return [
            'traveller' => $this->traveller->id,
            'component' => [
                'type' => $this->component->getComponentType(),
                'id' => $this->component->get()->id,
            ],
        ];
    }

    public static function fromLivewire($value): static
    {
        $traveller = BookingTraveller::find($value['traveller']);
        $component = InventoryTourRepository::getComponent($value['component']['type'], $value['component']['id']);
        return new static($component, $traveller);
    }
}
