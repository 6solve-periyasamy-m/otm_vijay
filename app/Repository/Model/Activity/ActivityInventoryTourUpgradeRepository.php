<?php

namespace App\Repository\Model\Activity;

use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentUpgradeRepository;

class ActivityInventoryTourUpgradeRepository extends ComponentUpgradeRepository
{
    private ActivityInventoryTourUpgrade $upgrade;

    public function __construct(ActivityInventoryTourUpgrade $upgrade)
    {
        $this->upgrade = $upgrade;
    }

    public function get(): ActivityInventoryTourUpgrade
    {
        return $this->upgrade;
    }

    public function update(array $data): ActivityInventoryTourUpgrade
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