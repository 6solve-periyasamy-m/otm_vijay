<?php

namespace Tests\Traits\Model;

use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierContract;

trait TestsSupplier
{
    public function generateSupplier(array $data = []): Supplier
    {
        return Supplier::factory()->create($data);
    }

    public function generateContract(Supplier|null $supplier = null, array $data = []): SupplierContract
    {
        $contract = SupplierContract::factory()->make($data);
        ($supplier ?? $this->generateSupplier())->contracts()->save($contract);
        return $contract;
    }
}