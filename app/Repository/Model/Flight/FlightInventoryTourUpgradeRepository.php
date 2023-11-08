<?php

namespace App\Repository\Model\Flight;

use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Traits\Component\IsFlight;

class FlightInventoryTourUpgradeRepository extends ComponentUpgradeRepository
{
    use IsFlight;

    private FlightInventoryTourUpgrade $upgrade;

    public function __construct(FlightInventoryTourUpgrade $upgrade)
    {
        $this->upgrade = $upgrade;
    }

    public function update(array $data): FlightInventoryTourUpgrade
    {
        $this->upgrade->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->upgrade->save();
    }

    public function get(): FlightInventoryTourUpgrade
    {
        return $this->upgrade;
    }

    public function delete(): bool
    {
        return $this->upgrade->delete();
    }

    public function isDeleted(): bool
    {
        return $this->upgrade->trashed();
    }

    public function __toString(): string
    {
        return "{$this->upgrade->description} - " . f_currency($this->upgrade->upgrade->tour_sales_price);
    }

    public static function find($id): FlightInventoryTourUpgrade|null
    {
        return FlightInventoryTourUpgrade::find($id);
    }
}
