<?php

namespace App\Repository\Model\Transport;

use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Traits\Component\IsTransport;

class TransportInventoryTourUpgradeRepository extends ComponentUpgradeRepository
{
    use IsTransport;

    private TransportInventoryTourUpgrade $upgrade;

    public function __construct(TransportInventoryTourUpgrade $upgrade)
    {
        $this->upgrade = $upgrade;
    }

    public function update(array $data): TransportInventoryTourUpgrade
    {
        $this->upgrade->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->upgrade->save();
    }

    public function get(): TransportInventoryTourUpgrade
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

    public static function find($id): TransportInventoryTourUpgrade|null
    {
        return TransportInventoryTourUpgrade::find($id);
    }
}
