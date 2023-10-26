<?php

namespace App\Repository\Model\Supplier;

use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractComponent;
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\LinksToComponents;
use Illuminate\Database\Eloquent\Model;

class SupplierContractRepository extends ModelRepository implements LinksToComponents
{
    private SupplierContract $contract;

    public function __construct(SupplierContract $contract)
    {
        $this->contract = $contract;
    }

    public function get(): Model
    {
        return $this->contract;
    }

    public function update(array $data): Model
    {
        $this->contract->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->contract->save();
    }

    public function delete(): bool
    {
        $this->contract->components()->delete();
        return $this->contract->delete();
    }

    public function isDeleted(): bool
    {
        return $this->contract->id === null;
    }

    public function __toString(): string
    {
        return $this->contract->purchase_order_number;
    }

    public function linkToComponent(InventoryRepository $component): bool
    {
        $contractComponent = new SupplierContractComponent();
        $contractComponent->componentRelation()->associate($component->get());
        $contractComponent->cost_per_unit = $component->getPurchasePrice() ?? 0;
        $contractComponent->quantity = 1;
        return $this->contract->components()->save($contractComponent);
    }
}