<?php

namespace Tests\Unit\Field\Order;

use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Order;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

class OrderCommissionTest extends DatabaseTestCase
{
    use TestsOrder;

    private function getOrder(float|null $commission, float $total = 1000, float $surcharge = 0, float $deposit = 0): Order
    {
        $order = $this->generateOrder(true, false, $total, $surcharge, $deposit);
        $order->commission = $commission;
        $order->save();
        return $order;
    }

    public function testNullCommission()
    {
        $this->assertNull($this->getOrder(null)->commission_amount);
    }

    public function testTenPercentCommissionOnOneThousand()
    {
        $this->assertEquals(100, $this->getOrder(10, 1000)->commission_amount);
    }

    public function testTwentyPercentCommissionOnOneThousand()
    {
        $this->assertEquals(200, $this->getOrder(20, 1000)->commission_amount);
    }

    public function testFivePercentOnOneHundred()
    {
        $this->assertEquals(5, $this->getOrder(5, 100)->commission_amount);
    }

    public function testZeroPercentOnOneHundred()
    {
        $this->assertEquals(0, $this->getOrder(0, 100)->commission_amount);
    }

    public function test10PercentCommissionWithPositiveAdjustments()
    {
        // Adjustments no longer affect commission. This should verify that
        $order = $this->getOrder(10);
        ManualAdjustment::create(['order_id' => $order->id, 'reason' => 'test', 'amount' => 100]);
        $this->assertEquals(100, $order->commission_amount);
    }

    public function test10PercentCommissionWithNegativeAdjustments()
    {
        // Adjustments no longer affect commission. This should verify that
        $order = $this->getOrder(10);
        ManualAdjustment::create(['order_id' => $order->id, 'reason' => 'test', 'amount' => -100]);
        $this->assertEquals(100, $order->commission_amount);
    }
}
