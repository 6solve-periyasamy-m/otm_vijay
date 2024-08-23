<?php

namespace Tests\Unit\Method\Repository\Transport;

use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsTransport;

/**
 * @covers \App\Repository\Model\Transport\TransportInventoryRepository
 */
class TransportInventoryRepositoryTest extends DatabaseTestCase
{
    use TestsTransport;

    /**
     * @return void
     * @covers \App\Repository\Model\Transport\TransportInventoryRepository::getPurchasePrice
     */
    public function testGetPurchasePrice(): void
    {
        $inventory = $this->generateTransportInventory();
        // Test 10 returns 10
        $inventory->purchase_price = 10;
        $inventory->save();
        $this->assertEquals(10, $inventory->repository->getPurchasePrice());
        // Test 0 returns 0
        $inventory->purchase_price = 0;
        $inventory->save();
        $this->assertEquals(0, $inventory->repository->getPurchasePrice());
        // Test 0.1 returns 0.1
        $inventory->purchase_price = 0.1;
        $inventory->save();
        $this->assertEquals(0.1, $inventory->repository->getPurchasePrice());
        // Test null returns 0
        $inventory->purchase_price = null;
        $inventory->save();
        $this->assertEquals(0, $inventory->repository->getPurchasePrice());
    }
}
