<?php

namespace Route\Admin\Order;

use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Payment\Payment;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

class OrderPaymentRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Payment::class;

    /**
     * @covers \App\Http\Controllers\Models\PaymentController::edit
     * @return void
     */
    public function testManualAdjustmentEdit(): void
    {
        $orderInstallment = $this->generatePayment($this->generateOrder(), 100);
        $this->performAllForRoute($this->class, 'update', 'payments.edit', ['order' => $orderInstallment->order, 'payment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\PaymentController::create
     * @return void
     */
    public function testManualAdjustmentCreate(): void
    {
        $orderInstallment = $this->generatePayment($this->generateOrder(), 100);
        $this->performAllForRoute($this->class, 'create', 'payments.create', ['order' => $orderInstallment->order, 'payment' => $orderInstallment,]);
    }
}
