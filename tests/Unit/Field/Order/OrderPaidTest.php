<?php

namespace Field\Order;

use App\Models\Order\Order;
use App\Models\Order\Payment\Payment;
use Tests\DatabaseTestCase;

class OrderPaidTest extends DatabaseTestCase
{
    public function testAmountPaidSingle()
    {
        $order = Order::factory()->create();
        $order->payments()->save(new Payment(['amount' => 100, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]));
        self::assertEquals(100, $order->paid);
    }

    public function testAmountPaidMultiple()
    {
        $order = Order::factory()->create();
        $order->payments()->save(new Payment(['amount' => 100, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]));
        $order->payments()->save(new Payment(['amount' => 200, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]));
        $order->payments()->save(new Payment(['amount' => 0.50, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]));
        self::assertEquals(300.5, $order->paid);
    }

    public function testAmountPaidWithRefund()
    {
        $order = Order::factory()->create();
        $order->payments()->save(new Payment(['amount' => 100, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]));
        $order->payments()->save(new Payment(['amount' => 100, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]));
        $order->payments()->save(new Payment(['amount' => -100, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Refund', 'paid_on' => now(),]));
        self::assertEquals(100, $order->paid);
    }
}
