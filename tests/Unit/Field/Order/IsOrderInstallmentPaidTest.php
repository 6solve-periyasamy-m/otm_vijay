<?php

namespace Field\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Models\Order\OrderInstallment::getPaidAttribute
 * @covers \App\Repository\Model\Order\OrderInstallmentRepository::isInstallmentPaid
 */
class IsOrderInstallmentPaidTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    public function testInstallmentPaidSinglePayment()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->generatePayment($installment->order, 100);
        $this->assertTrue($installment->paid);
    }

    public function testInstallmentPaidMultiplePayments()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->generatePayment($installment->order, 50);
        $this->generatePayment($installment->order, 50);
        $this->assertTrue($installment->paid);
    }

    public function testInstallmentPaidWithDeposit()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->generatePayment($order, 200);
        $this->assertTrue($installment->paid);
    }

    public function testInstallmentNotPaid()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->assertFalse($installment->paid);
    }

    public function testInstallmentNotPaidWithDeposit()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->assertFalse($installment->paid);
    }

    public function testInstallmentPartiallyPaidWithoutDeposit()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->generatePayment($installment->order, 50);
        $this->assertFalse($installment->paid);
    }

    public function testInstallmentPartiallyPaidWithDeposit()
    {
        $order = $this->generateOrder(true, true, 300, 50, 50);
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->generatePayment($installment->order, 100);
        $this->assertFalse($installment->paid);
    }

    public function testInstallmentPaidSinglePaymentWithBookingFee()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $order = $installment->order;
        $order->booking_fee = 100;
        $order->save();
        $this->generatePayment($installment->order, 200);
        $this->assertTrue($installment->paid);
    }

    public function testInstallmentPaidMultiplePaymentsWithBookingFee()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $order = $installment->order;
        $order->booking_fee = 100;
        $order->save();
        $this->generatePayment($installment->order, 100);
        $this->generatePayment($installment->order, 100);
        $this->assertTrue($installment->paid);
    }

    public function testInstallmentPaidWithDepositWithBookingFee()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $order->booking_fee = 100;
        $order->save();
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->generatePayment($order, 300);
        $this->assertTrue($installment->paid);
    }

    public function testInstallmentUnpaidSinglePaymentWithBookingFee()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $order = $installment->order;
        $order->booking_fee = 100;
        $order->save();
        $this->generatePayment($installment->order, 100);
        $this->assertFalse($installment->paid);
    }

    public function testInstallmentUnpaidMultiplePaymentsWithBookingFee()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $order = $installment->order;
        $order->booking_fee = 100;
        $order->save();
        $this->generatePayment($installment->order, 50);
        $this->generatePayment($installment->order, 50);
        $this->assertFalse($installment->paid);
    }

    public function testInstallmentUnpaidWithDepositWithBookingFee()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $order->booking_fee = 100;
        $order->save();
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->generatePayment($order, 200);
        $this->assertFalse($installment->paid);
    }
}