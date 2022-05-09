<?php

namespace Tests\Traits;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\Payment;
use App\Repository\OrderRepository;

trait TestsOrder
{
    use TestsTour;

    function generateOrder(): Order
    {
        return Order::factory()->create();
    }

    function generatePayment(?Order $order, float $amount): Payment
    {
        if (!isset($order)) $order = $this->generateOrder();
        $payment = new Payment(['amount' => $amount, 'customer_id' => 1, 'payment_method_id' => 1, 'payment_type' => 'Installment', 'paid_on' => now(),]);
        $order->payments()->save($payment);
        return $payment;
    }

    function generateOrderCustomer(bool $withIncluded = false, ?Order $order = null, float $tour_cost = 300, float $surcharge = 50): OrderCustomer
    {
        if (!isset($order)) $order = Order::factory()->create();

        $orderCustomer = OrderCustomer::factory()->make(['tour_cost' => $tour_cost, 'single_occupancy_surcharge' => $surcharge,]);
        $order->orderCustomers()->save($orderCustomer);

        OrderRepository::assignDefaultRooming($orderCustomer);
        $withIncluded && OrderRepository::addIncludedToCustomer($orderCustomer);

        return $orderCustomer;
    }

    function getDefaultCost(Order $order): float
    {
        $cost = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            $cost += $orderCustomer->tour_cost + ($orderCustomer->has_surcharge ? $orderCustomer->single_occupancy_surcharge : 0);
        }
        return $cost;
    }
}
