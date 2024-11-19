<?php

namespace App\Repository\Storage\Itinerary;

class ItineraryItem
{

    /**
     * @param string|null $name
     * @param string|null $type
     * @param int|null $sortKey
     * @param array<string, string> $details
     */
    public function __construct(
        public string|null $name,
        public string|null $type,
        public int|null $sortKey,
        public array $details
    ) {}

    public function clone(): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->sortKey,
            $this->details
        );
    }
}
