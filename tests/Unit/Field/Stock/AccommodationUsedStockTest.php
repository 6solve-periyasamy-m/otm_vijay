<?php

namespace Field\Stock;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Models\Accommodation\AccommodationInventory::getUsedStockAttribute
 * @covers \App\Repository\Model\Accommodation\AccommodationInventoryRepository::getUsedStock
 */
class AccommodationUsedStockTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testZeroIfUnused()
    {
        $inventory = $this->generateAccommodationInventory();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testZeroIfCancelled()
    {
        $inventory = $this->generateAccommodationInventory();
        $tourInventory = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker->primary_group);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testZeroIfDeleted()
    {
        $inventory = $this->generateAccommodationInventory();
        $tourInventory = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $orderInventory = $tourInventory->addToOrder($order->leadBooker->primary_group);
        $orderInventory->delete();
        $this->assertEquals(0, $inventory->used_stock);
    }

    public function testWithOneOrderCustomer()
    {
        $inventory = $this->generateAccommodationInventory();
        $tourInventory = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker->primary_group);
        $this->assertEquals(1, $inventory->used_stock);
    }

    public function testWithMultipleCustomersOnSingleOrder()
    {
        $inventory = $this->generateAccommodationInventory();
        $tourInventory = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory);
        $order = $this->generateOrder(true, false);
        $tourInventory->addToOrder($order->leadBooker->primary_group);
        for ($i = 0; $i < 5; $i++) {
            $orderCustomer = $this->generateOrderCustomer(false, $order);
            $tourInventory->addToOrder($orderCustomer->primary_group);
        }
        $this->assertEquals(6, $inventory->used_stock);
    }

    public function testWithMultipleOrders()
    {
        $inventory = $this->generateAccommodationInventory();
        $tourInventory = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory);
        for ($i = 0; $i < 5; $i++) {
            $order = $this->generateOrder(true, false);
            $tourInventory->addToOrder($order->leadBooker->primary_group);
            for ($ix = 0; $ix < 5; $ix++) {
                $orderCustomer = $this->generateOrderCustomer(false, $order);
                $tourInventory->addToOrder($orderCustomer->primary_group);
            }
        }
        $this->assertEquals(30, $inventory->used_stock);
    }
}