<?php

namespace App\Repository\Interfaces;

interface HasStockControl
{
    /**
     * @return int The amount of stock used on active orders
     */
    public function getUsedStock(): int;

    /**
     * @return int The total amount of stock that can be sold
     */
    public function getTotalStock(): int;

    /**
     * @return int The amount of stock available to be sold
     */
    public function getAvailableStock(): int;
}
