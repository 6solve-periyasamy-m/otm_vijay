<?php

namespace Tests\Unit\Method\Repository\Supplier\Contract;

use App\Models\Supplier\SupplierContract;
use Illuminate\Database\Eloquent\Model;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsSupplier;
use Tests\Traits\Model\TestsTour;

class AttachComponentTest extends DatabaseTestCase
{
    use TestsSupplier, TestsTour;

    public function testAttachAccommodationInventory()
    {
        $this->runAttachTest($this->generateAccommodationInventory());
    }

    public function testAttachActivityInventory()
    {
        $this->runAttachTest($this->generateActivityInventory());
    }

    public function testAttachFlightInventory()
    {
        $this->runAttachTest($this->generateFlightInventory());
    }

    public function testAttachTransportInventory()
    {
        $this->runAttachTest($this->generateTransportInventory());
    }

    public function testAttachMultiple()
    {
        $contract = $this->generateContract();
        $this->runAttachTest($this->generateAccommodationInventory(), $contract);
        $this->runAttachTest($this->generateActivityInventory(), $contract);
        $this->runAttachTest($this->generateFlightInventory(), $contract);
        $this->runAttachTest($this->generateTransportInventory(), $contract);
    }

    private function runAttachTest(Model $inventory, SupplierContract|null $contract = null)
    {
        $contract = $contract ?? $this->generateContract();
        \Log::info($inventory);
        $attached = $contract->repository->linkToComponent($inventory->repository);
        $this->assertTrue($attached, 'Attachment Failed');
        $class = $inventory::class;
        \Log::info($contract->components);
        foreach ($contract->components as $component) {
            \Log::info($component->toJson());
            if ($component->component_id === $inventory->id && $component->component_type === $class) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found ?? false, "Component of type {$class} {$inventory->id} not linked successfully");
    }
}