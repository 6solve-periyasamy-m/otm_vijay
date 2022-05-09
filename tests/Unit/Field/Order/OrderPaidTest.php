<?php

namespace Field\Order;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

class OrderPaidTest extends DatabaseTestCase
{
    use TestsOrder;

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
