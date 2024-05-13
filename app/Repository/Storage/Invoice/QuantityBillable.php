<?php

namespace App\Repository\Storage\Invoice;

class QuantityBillable
{
    private int $quantity;

    /**
     * @param string $description Description of the component
     * @param string $shared_key Specific shared key for the component
     * @param float $cost Cost of the component
     * @param bool $included Is the component included by default
     */
    public function __construct(
        public readonly string $description,
        public readonly string $shared_key,
        public readonly float $cost,
        public readonly bool $included,
    ) {
        $this->quantity = 0;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function addQuantity(): self
    {
        $this->quantity++;
        return $this;
    }
}