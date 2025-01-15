<?php

namespace App\Repository\Storage\Invoice;

use App\Repository\Model\Order\InvoiceGenerator;

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
        public string $description,
        public readonly string $shared_key,
        public readonly float $cost,
        public readonly bool $included,
    ) {
        $this->quantity = 0;
    }

    public function isGroupedBase(): bool
    {
        return $this->shared_key === InvoiceGenerator::BASE_KEY;
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

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}