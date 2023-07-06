<?php

namespace Field\Stock;

use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

/**
 * @covers \App\Models\Merchandise\Merchandise::getUsedStockAttribute
 * @covers \App\Repository\Model\Merchandise\MerchandiseInventoryTourRepository::getUsedStock
 */
class MerchandiseUsedStockTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testZeroIfUnused()
    {
        $inventory = $this->generateMerchandiseInventoryTour();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testZeroIfCancelled()
    {
        $tourInventory = $this->generateMerchandiseInventoryTour();
        $order = $this->generateOrder(true, false);
        $tourInventory->repository->grantToCustomer($order->leadBooker);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(0, $tourInventory->used_stock);
    }

    public function testZeroIfDeleted()
    {
        $tourInventory = $this->generateMerchandiseInventoryTour();
        $order = $this->generateOrder(true, false);
        $orderInventory = $tourInventory->repository->grantToCustomer($order->leadBooker);
        $orderInventory->delete();
        $this->assertEquals(0, $tourInventory->used_stock);
    }

    public function testWithOneOrderCustomer()
    {
        $tourInventory = $this->generateMerchandiseInventoryTour();
        $order = $this->generateOrder(true, false);
        $tourInventory->repository->grantToCustomer($order->leadBooker);
        $this->assertEquals(1, $tourInventory->used_stock);
    }

    public function testWithMultipleCustomersOnSingleOrder()
    {
        $tourInventory = $this->generateMerchandiseInventoryTour();
        $order = $this->generateOrder(true, false);
        $tourInventory->repository->grantToCustomer($order->leadBooker);
        for ($i = 0; $i < 5; $i++) {
            $orderCustomer = $this->generateOrderCustomer(false, $order);
            $tourInventory->repository->grantToCustomer($orderCustomer);
        }
        $this->assertEquals(6, $tourInventory->used_stock);
    }

    public function testWithMultipleOrders()
    {
        $tourInventory = $this->generateMerchandiseInventoryTour();
        for ($i = 0; $i < 5; $i++) {
            $order = $this->generateOrder(true, false);
            $tourInventory->repository->grantToCustomer($order->leadBooker);
            for ($ix = 0; $ix < 5; $ix++) {
                $orderCustomer = $this->generateOrderCustomer(false, $order);
                $tourInventory->repository->grantToCustomer($orderCustomer);
            }
        }
        $this->assertEquals(30, $tourInventory->used_stock);
    }
}
