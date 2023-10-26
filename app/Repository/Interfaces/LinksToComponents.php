<?php

namespace App\Repository\Interfaces;

use App\Repository\Abstracts\InventoryRepository;

interface LinksToComponents
{
    public function linkToComponent(InventoryRepository $component): bool;
}