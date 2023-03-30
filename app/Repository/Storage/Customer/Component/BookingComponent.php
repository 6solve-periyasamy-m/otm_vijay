<?php

namespace App\Repository\Storage\Customer\Component;

use App\Models\Booking\BookingTraveller;
use App\Repository\Abstracts\InventoryTourRepository;


class BookingComponent extends AbstractComponent
{
    private BookingTraveller $traveller;
    private bool $owned;

    public function __construct(InventoryTourRepository $component, BookingTraveller $traveller)
    {
        parent::__construct($component);
        $this->traveller = $traveller;
        $this->owned = $component->getBookingComponent($traveller) !== null;
    }

    public function getOwnedAttribute(): bool
    {
        return $this->owned;
    }

    public function getAvailableUpgrades(): array
    {
        // TODO: Implement getAvailableUpgrades() method.
    }

    public function purchaseForOne(): bool
    {
        // TODO: Implement purchaseForOne() method.
    }

    public function purchaseForAll(): bool
    {
        // TODO: Implement purchaseForAll() method.
    }

    public function sellForOne(): bool
    {
        // TODO: Implement sellForOne() method.
    }

    public function sellForAll(): bool
    {
        // TODO: Implement sellForAll() method.
    }
}
