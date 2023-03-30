<?php

namespace App\Repository\Storage\Customer\Component;

use App\Models\Order\OrderCustomer;
use App\Repository\Abstracts\InventoryTourRepository;

class OrderComponent extends AbstractComponent
{
    private OrderCustomer $traveller;
    private bool $owned;

    public function __construct(InventoryTourRepository $component, OrderCustomer $traveller)
    {
        parent::__construct($component);
        $this->traveller = $traveller;
        $this->owned = $component->getOrderComponent($traveller) !== null;
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
