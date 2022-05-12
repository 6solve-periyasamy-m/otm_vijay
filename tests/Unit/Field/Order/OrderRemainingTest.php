<?php

namespace Field\Order;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers Order::getRemainingAttribute
 * @covers \App\Repository\OrderRepository::getRemainingToPay
 */
class OrderRemainingTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testWithNoPayments()
    {
        $order = $this->generateOrder();

        $this->assertEquals($this->getDefaultCost($order), $order->remaining);
    }

    public function testWithOnePayment()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, 100);
        $this->assertEquals($this->getDefaultCost($order) - 100, $order->remaining);
    }

    public function testWithTwoPayments()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, 100);
        $this->generatePayment($order, 100);
        $this->assertEquals($this->getDefaultCost($order) - 200, $order->remaining);
    }

    public function testFullPaidWithOnePayment()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, $this->getDefaultCost($order));
        $this->assertEquals(0, $order->remaining);
    }

    public function testFullPaidWithTwoPayment()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, $this->getDefaultCost($order) - 100);
        $this->generatePayment($order, 100);
        $this->assertEquals(0, $order->remaining);
    }

    public function testPartiallyPaidWithOneRefund()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, $this->getDefaultCost($order));
        $this->generatePayment($order, -100);
        $this->assertEquals(100, $order->remaining);
    }

    public function testWithOneNegativeAdjustment()
    {
        $order = $this->generateOrder();
        $this->generateManualAdjustment(-100, $order);
        $this->assertEquals($this->getDefaultCost($order) - 100, $order->remaining);
    }

    public function testWithOnePositiveAdjustment()
    {
        $order = $this->generateOrder();
        $this->generateManualAdjustment(100, $order);
        $this->assertEquals($this->getDefaultCost($order) + 100, $order->remaining);
    }

    public function testWithOnePositiveAndOneNegativeAdjustment()
    {
        $order = $this->generateOrder();
        $this->generateManualAdjustment(-100, $order);
        $this->generateManualAdjustment(100, $order);
        $this->assertEquals($this->getDefaultCost($order), $order->remaining);
    }

    public function testWithTwoPositiveAdjustments()
    {
        $order = $this->generateOrder();
        $this->generateManualAdjustment(100, $order);
        $this->generateManualAdjustment(100, $order);
        $this->assertEquals($this->getDefaultCost($order) + 200, $order->remaining);
    }

    public function testWithTwoNegativeAdjustments()
    {
        $order = $this->generateOrder();
        $this->generateManualAdjustment(-100, $order);
        $this->generateManualAdjustment(-100, $order);
        $this->assertEquals($this->getDefaultCost($order) - 200, $order->remaining);
    }

    public function testWithCancelledOrder()
    {
        $order = $this->generateOrder();
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(0, $order->remaining);
    }
}
