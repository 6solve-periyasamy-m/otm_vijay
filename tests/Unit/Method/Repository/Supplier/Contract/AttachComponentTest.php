<?php

namespace Tests\Unit\Method\Repository\Supplier\Contract;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Flight\FlightInventory;
use App\Models\Transport\TransportInventory;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsSupplier;
use Tests\Traits\Model\TestsTour;

class AttachComponentTest extends DatabaseTestCase
{
    use TestsSupplier, TestsTour;

    public function testAttachAccommodationInventory()
    {
        $inventory = $this->generateAccommodationInventory();
        $contract = $this->generateContract();
        $contract->repository->linkToComponent($inventory->repository);
        $component = $contract->components->first();
        $this->assertEquals(AccommodationInventory::class, $component->component_type);
        $this->assertEquals($inventory->id, $component->component_id);
    }

    public function testAttachActivityInventory()
    {
        $inventory = $this->generateActivityInventory();
        $contract = $this->generateContract();
        $contract->repository->linkToComponent($inventory->repository);
        $component = $contract->components->first();
        $this->assertEquals(ActivityInventory::class, $component->component_type);
        $this->assertEquals($inventory->id, $component->component_id);
    }

    public function testAttachFlightInventory()
    {
        $inventory = $this->generateFlightInventory();
        $contract = $this->generateContract();
        $contract->repository->linkToComponent($inventory->repository);
        $component = $contract->components->first();
        $this->assertEquals(FlightInventory::class, $component->component_type);
        $this->assertEquals($inventory->id, $component->component_id);
    }

    public function testAttachTransportInventory()
    {
        $inventory = $this->generateTransportInventory();
        $contract = $this->generateContract();
        $contract->repository->linkToComponent($inventory->repository);
        $component = $contract->components->first();
        $this->assertEquals(TransportInventory::class, $component->component_type);
        $this->assertEquals($inventory->id, $component->component_id);
    }
}