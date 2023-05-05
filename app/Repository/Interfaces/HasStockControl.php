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

    /**
     * @return bool Whether stock levels should be checked
     */
    public function isStockControlActive(): bool;

    /**
     * @param int $amount The amount of stock that wishes to be used
     * @return bool Whether there is enough stock, or if stock control is inactive
     */
    public function hasEnoughStock(int $amount = 1): bool;
}
