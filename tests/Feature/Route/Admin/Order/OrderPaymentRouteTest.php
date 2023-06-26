<?php

namespace Route\Admin\Order;

use App\Models\Order\Payment\Payment;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

class OrderPaymentRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Payment::class;

    /**
     * @covers \App\Http\Controllers\Admin\Order\Payment\PaymentController::edit
     * @return void
     */
    public function testPaymentEdit(): void
    {
        $orderInstallment = $this->generatePayment($this->generateOrder(), 100);
        $this->performAllForRoute($this->class, 'update', 'payments.edit', ['order' => $orderInstallment->order, 'payment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\Payment\PaymentController::create
     * @return void
     */
    public function testPaymentCreate(): void
    {
        $orderInstallment = $this->generatePayment($this->generateOrder(), 100);
        $this->performAllForRoute($this->class, 'create', 'payments.create', ['order' => $orderInstallment->order, 'payment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\Payment\PaymentController::destroy
     * @return void
     */
    public function testPaymentDelete(): void
    {
        $orderInstallment = $this->generatePayment($this->generateOrder(), 100);
        $this->performAllForDeleteRoute($this->class, 'delete', 'payments.delete', ['order' => $orderInstallment->order, 'payment' => $orderInstallment,], $orderInstallment);
    }
}
