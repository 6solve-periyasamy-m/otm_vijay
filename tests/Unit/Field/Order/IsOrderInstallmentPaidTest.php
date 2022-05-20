<?php

namespace Field\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Models\Order\OrderInstallment::getPaidAttribute
 * @covers \App\Repository\StaticOrderRepository::isInstallmentPaid
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
}