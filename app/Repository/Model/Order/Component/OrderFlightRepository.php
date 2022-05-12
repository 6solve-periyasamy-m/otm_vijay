<?php

namespace App\Repository\Model\Order\Component;

use App\Models\Order\Component\OrderFlight;
use App\Repository\Abstracts\OrderComponentRepository;
use Illuminate\Database\Eloquent\Model;

class OrderFlightRepository extends OrderComponentRepository
{
    private OrderFlight $orderComponent;

    public function __construct(OrderFlight $orderComponent)
    {
        $this->orderComponent = $orderComponent;
    }

    public function get(): Model
    {
        return $this->orderComponent;
    }

    public function update(array $data): Model
    {
        $this->orderComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->orderComponent->save();
    }

    public function delete(): bool
    {
        return $this->orderComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->orderComponent->trashed();
    }

    public function __toString(): string
    {
        return "{$this->orderComponent->tourComponent}";
    }

    public function getTourComponentType(): string
    {
        return $this->orderComponent->tourComponent->tour_component_type;
    }

    public function getCost(): float
    {
        return $this->orderComponent->cost;
    }
}
