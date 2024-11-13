<?php

namespace Tests\Unit\Field\Order\OrderInstallment;

use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

class InstallmentRemainingTest extends DatabaseTestCase
{
    use TestsOrder;
    public function testRemainingWithOneTraveller(): void
    {
        $order = $this->generateOrder(true, false, 500, 0, 0);
        $installment1 = $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $installment2 = $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $this->generatePayment($order, 100);

        $this->assertEquals(0, $installment1->refresh()->repository->getRemainingAmount());
        $this->assertEquals(100, $installment2->refresh()->repository->getRemainingAmount());
    }

    public function testRemainingWithTwoTravellers(): void
    {
        $order = $this->generateOrder(true, false, 500, 0, 0);
        $this->generateOrderCustomer(false, $order, 500, 0);
        $installment1 = $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $installment2 = $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $this->generatePayment($order, 200);

        $this->assertEquals(0, $installment1->refresh()->repository->getRemainingAmount());
        $this->assertEquals(200, $installment2->refresh()->repository->getRemainingAmount());
    }

    public function testRemainingWithTwoTravellersAndOneNonPaying(): void
    {

        $order = $this->generateOrder(true, false, 500, 0, 0);
        $this->generateOrderCustomer(false, $order, 500, 0);
        $nonPaying = $this->generateOrderCustomer(false, $order, 500, 0);
        $nonPaying->update(['is_charged' => false]);
        $nonPaying->save();
        $installment1 = $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $installment2 = $this->generateOrderInstallment(now()->addDays(10), 100, $order);
        $this->generatePayment($order, 200);

        $this->assertEquals(0, $installment1->refresh()->repository->getRemainingAmount());
        $this->assertEquals(200, $installment2->refresh()->repository->getRemainingAmount());
    }
}
