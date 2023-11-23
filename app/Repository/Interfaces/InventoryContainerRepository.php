<?php

namespace App\Repository\Interfaces;

use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\ModelRepository;

abstract class InventoryContainerRepository extends ModelRepository
{
    public abstract function linkToComponent(InventoryRepository $component): bool;

    public abstract function massAssociate(string $class, array $items, array $attributes = []);

    public abstract function getId();
}