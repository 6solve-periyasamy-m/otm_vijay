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
        if ($this->owned) return false;
        $this->component->grantToCustomer($this->traveller);
        return true;
    }

    public function purchaseForAll(): bool
    {
        $this->component->purchaseForAll($this->traveller->order);
        return true;
    }

    public function sellForOne(): bool
    {
        return false;
    }

    public function sellForAll(): bool
    {
        return false;
    }
}
