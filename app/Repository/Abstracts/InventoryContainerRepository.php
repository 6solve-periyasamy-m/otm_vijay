<?php

namespace App\Repository\Abstracts;

use App\Repository\Interfaces\HasComponentType;
use Carbon\Carbon;

abstract class InventoryContainerRepository extends ModelRepository implements HasComponentType
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
    public abstract function getInventory(): ?InventoryRepository;

    public function getSalesPrice(): ?float
    {
        return $this->getInventory()->getSalesPrice();
    }

    public function getPurchasePrice(): ?float
    {
        return $this->getInventory()->getPurchasePrice();
    }

    public function getMargin(): ?float
    {
        $p = $this->getPurchasePrice();
        $s = $this->getCost();
        if (empty($p)) return null;
        return sigfig((($s-$p)/$p)*100);
    }

    public function getStartTime(): Carbon
    {
        return $this->getInventory()->getStartTime();
    }

    public function getEndTime(): Carbon
    {
        return $this->getInventory()->getEndTime();
    }
}
