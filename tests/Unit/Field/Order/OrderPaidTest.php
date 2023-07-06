<?php

namespace Field\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

/**
 * @covers \App\Models\Order\Order::getPaidAttribute
 */
class OrderPaidTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    public function testAmountPaidSingle()
    {
        $payment = $this->generatePayment(null, 100);
        self::assertEquals(100, $payment->order->paid);
    }

    public function testAmountPaidMultiple()
    {
        $payment = $this->generatePayment(null, 100);
        $order = $payment->order;
        $this->generatePayment($order, 200);
        $this->generatePayment($order, 0.5);
        self::assertEquals(300.5, $order->paid);
    }

    public function testAmountPaidWithRefund()
    {
        $payment = $this->generatePayment(null, 100);
        $order = $payment->order;
        $this->generatePayment($order, 100);
        $this->generatePayment($order, -100);
        self::assertEquals(100, $order->paid);
    }
}
