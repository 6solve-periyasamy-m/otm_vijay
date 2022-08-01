<?php

namespace App\Repository\Abstracts;

use Carbon\Carbon;

abstract class InventoryContainerRepository extends ModelRepository
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
    public abstract function getInventory(): ?InventoryRepository;
    public abstract function getComponentType(): string;

    public function getPurchasePrice(): ?float
    {
        return $this->getInventory()->getPurchasePrice();
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
