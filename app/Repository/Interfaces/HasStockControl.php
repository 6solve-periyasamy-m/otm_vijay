<?php

namespace App\Repository\Interfaces;

interface HasStockControl
{
    public function getUsedStock(): int;
    public function getTotalStock(): int;
    public function getAvailableStock(): int;
}