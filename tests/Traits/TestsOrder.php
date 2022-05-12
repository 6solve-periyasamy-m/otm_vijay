<?php

namespace Tests\Traits;

use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use App\Repository\OrderRepository;
use Carbon\Carbon;

trait TestsOrder
{
    use TestsTour;

    function generateOrder(): Order
    {
        $order = Order::factory()->create();
        $order->lead_booker_id = $this->generateOrderCustomer(true, $order)->id;
        $order->save();
        return $order;
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
        if (!isset($order)) $order = $this->generateOrder();

        $orderCustomer = OrderCustomer::factory()->make(['tour_cost' => $tour_cost, 'single_occupancy_surcharge' => $surcharge,]);
        $order->orderCustomers()->save($orderCustomer);

        OrderRepository::assignDefaultRooming($orderCustomer);
        $withIncluded && OrderRepository::addIncludedToCustomer($orderCustomer);

        return $orderCustomer;
    }

    function generateOrderInstallment(Carbon $date, float $amount, ?Order $order = null): OrderInstallment
    {
        if (!isset($order)) $order = $this->generateOrder();

        $installment = new OrderInstallment([
            'amount' => $amount,
            'due_on' => $date,
        ]);
        $order->installments()->save($installment);

        return $installment;
    }

    function generateManualAdjustment(float $amount, ?Order $order = null): ManualAdjustment
    {
        if (!isset($order)) $order = $this->generateOrder();

        $adjustment = new ManualAdjustment([
            'amount' => $amount,
            'date' => Carbon::now(),
            'reason' => 'Test Reason'
        ]);
        $order->adjustments()->save($adjustment);

        return $adjustment;
    }

    function generateOrderCustomerAdjustment(float $amount, Order|OrderCustomer|null $model = null): OrderCustomerAdjustment
    {
        if ($model instanceof OrderCustomer) $orderCustomer = $model;
        elseif ($model instanceof Order) $orderCustomer = $model->leadBooker;
        else $orderCustomer = $this->generateOrder()->leadBooker;

        $adjustment = new OrderCustomerAdjustment([
            'amount' => $amount,
            'date' => Carbon::now(),
            'reason' => 'Test Reason'
        ]);
        $orderCustomer->adjustments()->save($adjustment);

        return $adjustment;
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
