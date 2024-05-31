<?php

namespace Field\Order;

use App\Models\Helper\Enum\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

/**
 * @covers \App\Models\Order\Order::getStatusAttribute
 * @covers \App\Repository\Model\Order\OrderRepository::getOrderStatus Parent method
 */
class OrderStatusTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    public function testBalanceOutstandingNoInstallments()
    {
        $order = $this->generateOrder();
        $order->tour->update(['final_payment' => now()->addDays(2)]);
        $order->tour->save();
        $this->assertEquals(OrderStatus::BALANCE_OUTSTANDING, $order->status);
    }

    public function testBalanceOutstandingWithNoPaidInstallments()
    {
        $order = $this->generateOrderInstallment(now()->addDays(2), 100)->order;
        $this->assertEquals(OrderStatus::BALANCE_OUTSTANDING, $order->status);
    }

    public function testBalanceOutstandingWithAPaidInstallments()
    {
        $order = $this->generateOrderInstallment(now()->addDays(2), 100)->order;
        $this->generateOrderInstallment(now()->addDays(4), 100, $order);
        $this->generatePayment($order, 100);
        $this->assertEquals(OrderStatus::BALANCE_OUTSTANDING, $order->status);
    }

    public function testPaymentOverdueWithNoPaidInstallments()
    {
        $order = $this->generateOrderInstallment(now()->subDays(2), 100)->order;
        $order->repository->refresh();
        $this->assertEquals(OrderStatus::PAYMENT_OVERDUE, $order->status);
    }

    public function testPaymentOverdueWithAPaidInstallments()
    {
        $order = $this->generateOrderInstallment(now()->subDays(2), 100)->order;
        $this->generateOrderInstallment(now()->subDays(4), 100, $order);
        $this->generatePayment($order, 100);
        $order->repository->refresh();
        $this->assertEquals(OrderStatus::PAYMENT_OVERDUE, $order->status);
    }

    public function testPaidInFullNoInstallments()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, $this->getDefaultCost($order));
        $order->repository->refresh();
        $this->assertEquals(OrderStatus::PAID_IN_FULL, $order->status);
    }

    public function testPaidInFullWithInstallments()
    {
        $order = $this->generateOrder();
        $this->generatePayment($order, $this->getDefaultCost($order));
        $this->generateOrderInstallment(now()->subDays(10), 100, $order);
        $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $order->repository->refresh();
        $this->assertEquals(OrderStatus::PAID_IN_FULL, $order->status);
    }

    public function testCancelledFullRefundNoPayments()
    {
        $order = $this->generateOrder();
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(OrderStatus::CANCELLED_FULL_REFUND, $order->status);
    }

    public function testCancelledFullRefundWithPayments()
    {
        $order = $this->generateOrder();
        $this->generatepayment($order, 100);
        $this->generatepayment($order, -100);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(OrderStatus::CANCELLED_FULL_REFUND, $order->status);
    }

    public function testDepositHeldSinglePayment()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $this->generatepayment($order, 100);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(OrderStatus::CANCELLED_DEPOSIT_HELD, $order->status);
    }

    public function testDepositHeldMultiplePayment()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $this->generatepayment($order, 50);
        $this->generatepayment($order, 50);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(OrderStatus::CANCELLED_DEPOSIT_HELD, $order->status);
    }

    public function testRefundRequiredSinglePayment()
    {
        $order = $this->generateOrder();
        $this->generatepayment($order, 5000);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(OrderStatus::CANCELLED_REFUND_REQUIRED, $order->status);
    }

    public function testRefundRequiredMultiplePayment()
    {
        $order = $this->generateOrder();
        $this->generatepayment($order, 100);
        $this->generatepayment($order, 5000);
        $order->cancelled = true;
        $order->save();
        $this->assertEquals(OrderStatus::CANCELLED_REFUND_REQUIRED, $order->status);
    }

    public function testOverpaid()
    {
        $order = $this->generateOrder();
        $this->generatepayment($order, 5000);
        $order->repository->refresh();
        $this->assertEquals(OrderStatus::OVERPAID, $order->status);
    }
}
