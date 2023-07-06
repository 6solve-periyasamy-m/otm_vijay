<?php

namespace Field\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

/**
 * @covers \App\Models\Order\OrderInstallment::getPaidAttribute
 * @covers \App\Repository\Model\Order\OrderInstallmentRepository::isInstallmentPaid
 */
class OrderInstallmentAmountPaidTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    public function testInstallmentPaidSinglePayment()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->generatePayment($installment->order, 100);
        $this->assertEquals(100, $installment->repository->getAmountPaid());
    }

    public function testInstallmentPaidMultiplePayments()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->generatePayment($installment->order, 50);
        $this->generatePayment($installment->order, 50);
        $this->assertEquals(100, $installment->repository->getAmountPaid());
    }

    public function testInstallmentPaidWithDeposit()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->generatePayment($order, 200);
        $this->assertEquals(100, $installment->repository->getAmountPaid());
    }

    public function testInstallmentNotPaid()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->assertEquals(0, $installment->repository->getAmountPaid());
    }

    public function testInstallmentNotPaidWithDeposit()
    {
        $order = $this->generateOrder(true, true, 300, 50, 100);
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->assertEquals(0, $installment->repository->getAmountPaid());
    }

    public function testInstallmentPartiallyPaidWithoutDeposit()
    {
        $installment = $this->generateOrderInstallment(now(), 100);
        $this->generatePayment($installment->order, 50);
        $this->assertEquals(50, $installment->repository->getAmountPaid());
    }

    public function testInstallmentPartiallyPaidWithDeposit()
    {
        $order = $this->generateOrder(true, true, 300, 50, 50);
        $installment = $this->generateOrderInstallment(now(), 100, $order);
        $this->generatePayment($installment->order, 100);
        $this->assertEquals(50, $installment->repository->getAmountPaid());
    }
}
