<?php

namespace App\Repository\Storage\Customer\Component;

class OrderComponent extends AbstractComponent
{

    public function getOwnedAttribute(): bool
    {
        // TODO: Implement getOwnedAttribute() method.
        return false;
    }

    public function getAvailableUpgrades(): array
    {
        return $this->component->getAvailableForUpgrade();
    }

    public function purchaseForOne(): bool
    {
        // TODO: Implement purchaseForOne() method.
        return false;
    }

    public function purchaseForAll(): bool
    {
        // TODO: Implement purchaseForAll() method.
        return false;
    }

    // Order Components will never be sellable

    public function sellForOne(): bool
    {
        return false;
    }

    public function sellForAll(): bool
    {
        return false;
    }
}
