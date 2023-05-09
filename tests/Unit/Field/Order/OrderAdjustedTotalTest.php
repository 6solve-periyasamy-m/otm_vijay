<?php

namespace Field\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

/**
 * @covers \App\Models\Order\Order::getTotalAdjustmentsAttribute
 * @covers \App\Repository\Model\Order\OrderRepository::getTotalAdjustedValue parent method of Order::getAdjustmentValue
 */
class OrderAdjustedTotalTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    public function testWithNoAdjustments()
    {
        $order = $this->generateOrder();
        $this->assertEquals(0, $order->total_adjustments);
    }

    public function testWithSingleOrderAdjustment()
    {
        $adjustment = $this->generateManualAdjustment(100);
        $this->assertEquals(100, $adjustment->order->total_adjustments);
    }

    public function testWithMultipleOrderAdjustments()
    {
        $adjustment = $this->generateManualAdjustment(100);
        $this->generateManualAdjustment(100, $adjustment->order);
        $this->assertEquals(200, $adjustment->order->total_adjustments);
    }

    public function testWithMultipleOrderAdjustmentsWithNegative()
    {
        $adjustment = $this->generateManualAdjustment(100);
        $this->generateManualAdjustment(-100, $adjustment->order);
        $this->assertEquals(0, $adjustment->order->total_adjustments);
    }

    public function testWithSingleCustomerAdjustment()
    {
        $adjustment = $this->generateOrderCustomerAdjustment(100);
        $this->assertEquals(100, $adjustment->orderCustomer->order->total_adjustments);
    }

    public function testWithMultipleCustomerAdjustments()
    {
        $adjustment = $this->generateOrderCustomerAdjustment(100);
        $this->generateOrderCustomerAdjustment(100, $adjustment->orderCustomer->order);
        $this->assertEquals(200, $adjustment->orderCustomer->order->total_adjustments);
    }

    public function testWithMultipleCustomerAdjustmentsWithNegative()
    {
        $adjustment = $this->generateOrderCustomerAdjustment(100);
        $this->generateOrderCustomerAdjustment(-100, $adjustment->orderCustomer->order);
        $this->assertEquals(0, $adjustment->orderCustomer->order->total_adjustments);
    }

    public function testWithMixedAdjustments()
    {
        $adjustment = $this->generateManualAdjustment(100);
        $this->generateOrderCustomerAdjustment(100, $adjustment->order);
        $this->assertEquals(200, $adjustment->order->total_adjustments);
    }
}
