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
        if (method_exists($this, 'getUpgradeParent')) {
            $upgrade = $this->getUpgradeParent();
            if ($upgrade->id === $this->get()->id) {
                $p = $this->getPurchasePrice();
            } else {
                $p = ($this->getPurchasePrice() - $this->getUpgradeParent()->repository->getPurchasePrice());
            }
        } else {
            $p = $this->getPurchasePrice();
        }
        $s = $this->getCost();
        if (empty($p)) return null;
        return sigfig((($s-$p)/$p)*100);
    }

    public function getStartTime(): Carbon|null
    {
        return $this->getInventory()->getStartTime();
    }

    public function getEndTime(): Carbon|null
    {
        return $this->getInventory()->getEndTime();
    }
}
