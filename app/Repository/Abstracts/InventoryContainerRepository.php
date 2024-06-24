<?php

namespace App\Repository\Abstracts;

use App\Repository\Abstracts\Interfaces\BelongsOnItinerary;
use App\Repository\Interfaces\HasComponentType;
use Carbon\Carbon;

abstract class InventoryContainerRepository extends ModelRepository implements HasComponentType, BelongsOnItinerary
{
    abstract public function getTourComponentType(): string;
    abstract public function getCost(): float;
    abstract public function getInventory(): ?InventoryRepository;

    public function getSalesPrice(): ?float
    {
        return $this->getInventory()?->getSalesPrice();
    }

    public function getPurchasePrice(): ?float
    {
        return $this->getInventory()?->getPurchasePrice();
    }

    public function getLocalPurchasePrice(): ?float
    {
        return $this->getInventory()?->getLocalPurchasePrice();
    }

    public function getMargin(): ?float
    {
        if (method_exists($this, 'getUpgradeParent')) {
            $upgrade = $this->getUpgradeParent();
            if ($upgrade->id === $this->get()->id) {
                $purchase = $this->getLocalPurchasePrice();
            } else {
                $purchase = ($this->getLocalPurchasePrice() - $this->getUpgradeParent()->repository->getLocalPurchasePrice());
            }
        } else {
            $purchase = $this->getLocalPurchasePrice();
        }
        $sales = $this->getCost();
        if (empty($purchase)) return null;
        return sigfig((($sales-$purchase)/$purchase)*100);
    }

    public function getStartTime(): Carbon|null
    {
        return $this->getInventory()?->getStartTime();
    }

    public function getEndTime(): Carbon|null
    {
        return $this->getInventory()?->getEndTime();
    }
}
