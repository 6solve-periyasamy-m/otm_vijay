<?php

namespace Field\Stock;

use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

/**
 * @covers \App\Models\Flight\FlightInventory::getUsedStockAttribute
 * @covers \App\Repository\Model\Flight\FlightInventoryRepository::getUsedStock
 */
class FlightUsedStockTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testZeroIfUnused()
    {
        $inventory = $this->generateFlightInventory();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testZeroIfCancelled()
    {
        $inventory = $this->generateFlightInventory();
        $tourInventory = $this->generateFlightInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testZeroIfDeleted()
    {
        $inventory = $this->generateFlightInventory();
        $tourInventory = $this->generateFlightInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $orderInventory = $tourInventory->addToOrder($order->leadBooker);
        $orderInventory->delete();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testWithOneOrderCustomer()
    {
        $inventory = $this->generateFlightInventory();
        $tourInventory = $this->generateFlightInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker);
        $this->assertEquals(1, $inventory->used_stock);
    }

    public function testWithMultipleCustomersOnSingleOrder()
    {
        $inventory = $this->generateFlightInventory();
        $tourInventory = $this->generateFlightInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker);
        for ($i = 0; $i < 5; $i++) {
            $orderCustomer = $this->generateOrderCustomer(false, $order);
            $tourInventory->addToOrder($orderCustomer);
        }
        $this->assertEquals(6, $inventory->used_stock);
    }

    public function testWithMultipleOrders()
    {
        $inventory = $this->generateFlightInventory();
        $tourInventory = $this->generateFlightInventoryTour(null, 'Included', 100, $inventory);
        for ($i = 0; $i < 5; $i++) {
            $order = $this->generateOrder(true, false);
            $tourInventory->addToOrder($order->leadBooker);
            for ($ix = 0; $ix < 5; $ix++) {
                $orderCustomer = $this->generateOrderCustomer(false, $order);
                $tourInventory->addToOrder($orderCustomer);
            }
        }
        $this->assertEquals(30, $inventory->used_stock);
    }
}
