<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Traits\Component\IsAccommodation;

class AccommodationInventoryTourUpgradeRepository extends ComponentUpgradeRepository
{
    use IsAccommodation;

    private AccommodationInventoryTourUpgrade $upgrade;

    public function __construct(AccommodationInventoryTourUpgrade $upgrade)
    {
        $this->upgrade = $upgrade;
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

    public function get(): AccommodationInventoryTourUpgrade
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

    public static function find($id): AccommodationInventoryTourUpgrade|null
    {
        return AccommodationInventoryTourUpgrade::find($id);
    }
}
