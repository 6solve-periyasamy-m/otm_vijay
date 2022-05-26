<?php

namespace Field\Stock;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Models\Tour\Merchandise::getUsedStockAttribute
 * @covers \App\Repository\StockRepository::getExtraStock
 */
class MerchandiseUsedStockTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testZeroIfUnused()
    {
        $inventory = $this->generateMerchandise(null, 'Included', 100);
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testZeroIfCancelled()
    {
        $tourInventory = $this->generateMerchandise(null, 'Included', 100);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(0, $tourInventory->used_stock);
    }

    public function testZeroIfDeleted()
    {
        $tourInventory = $this->generateMerchandise(null, 'Included', 100);
        $order = $this->generateOrder(true, false);
        $orderInventory = $tourInventory->addToOrder($order->leadBooker);
        $orderInventory->delete();
        $this->assertEquals(0, $tourInventory->used_stock);
    }

    public function testWithOneOrderCustomer()
    {
        $tourInventory = $this->generateMerchandise(null, 'Included', 100);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker);
        $this->assertEquals(1, $tourInventory->used_stock);
    }

    public function testWithMultipleCustomersOnSingleOrder()
    {
        $tourInventory = $this->generateMerchandise(null, 'Included', 100);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker);
        for ($i = 0; $i < 5; $i++) {
            $orderCustomer = $this->generateOrderCustomer(false, $order);
            $tourInventory->addToOrder($orderCustomer);
        }
        $this->assertEquals(6, $tourInventory->used_stock);
    }

    public function testWithMultipleOrders()
    {
        $tourInventory = $this->generateMerchandise(null, 'Included', 100);
        for ($i = 0; $i < 5; $i++) {
            $order = $this->generateOrder(true, false);
            $tourInventory->addToOrder($order->leadBooker);
            for ($ix = 0; $ix < 5; $ix++) {
                $orderCustomer = $this->generateOrderCustomer(false, $order);
                $tourInventory->addToOrder($orderCustomer);
            }
        }
        $this->assertEquals(30, $tourInventory->used_stock);
    }
}