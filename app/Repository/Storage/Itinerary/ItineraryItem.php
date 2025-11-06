<?php

namespace App\Repository\Storage\Itinerary;

class ItineraryItem
{

    /**
     * @param string|null $name
     * @param string|null $type
     * @param int|null $sortKey
     * @param int|null $orderKey
     * @param array<string, string> $details
     */
    public function __construct(
        public string|null $name,
        public string|null $type,
        public int|null $sortKey,
        public int|null $orderKey,
        public array $details
    ) {}

    public function clone(): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->sortKey,
            $this->orderKey,
            $this->details
        );
    }

    public function compare(ItineraryItem $item): int
    {
        if ($this->orderKey !== $item->orderKey) {
            return $this->orderKey >= $item->orderKey ? 1 : -1;
        }
        return $this->sortKey >= $item->sortKey ? 1 : -1;
    }
}
