<?php

namespace Field\Order;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers Order::getHasAtolAttribute
 * @covers \App\Repository\Model\Order\OrderRepository::hasFlight Parent method
 */
class OrderHasFlightTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testHasNoFlight()
    {
        $order = $this->generateOrder(true, false);
        $this->assertFalse($order->has_atol);
    }

    public function testLeadHasFlight()
    {
        $order = $this->generateOrder(true, true);
        $this->generateFlightInventoryTour($order->tour, 'Included', 300)->addToOrder($order->leadBooker);
        $this->assertTrue($order->has_atol);
    }

    public function testBothHaveFlights()
    {
        $order = $this->generateOrder(true, true);
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $flight = $this->generateFlightInventoryTour($order->tour, 'Included', 300);
        $flight->addToOrder($order->leadBooker);
        $flight->addToOrder($orderCustomer);
        $this->assertTrue($order->has_atol);
    }

    public function testAdditionalHasFlightButLeadDoesNot()
    {
        $order = $this->generateOrder(true, true);
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $this->generateFlightInventoryTour($order->tour, 'Included', 300)->addToOrder($orderCustomer);
        $this->assertTrue($order->has_atol);
    }
}
