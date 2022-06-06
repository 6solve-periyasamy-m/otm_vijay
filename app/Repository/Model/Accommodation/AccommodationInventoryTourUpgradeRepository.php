<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentUpgradeRepository;

class AccommodationInventoryTourUpgradeRepository extends ComponentUpgradeRepository
{
    private AccommodationInventoryTourUpgrade $upgrade;

    public function __construct(AccommodationInventoryTourUpgrade $upgrade)
    {
        $this->upgrade = $upgrade;
    }

    public function get(): AccommodationInventoryTourUpgrade
    {
        return $this->upgrade;
    }

    public function update(array $data): AccommodationInventoryTourUpgrade
    {
        $this->upgrade->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->upgrade->save();
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
}