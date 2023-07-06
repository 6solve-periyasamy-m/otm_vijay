<?php

namespace Route\Admin\Access\Order;

use App\Models\Order\Order;
use Tests\Bases\Authentication\AuthenticatedRouteTestCase;
use Tests\Traits\Model\TestsOrder;

class OrderInstallmentRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Order::class;

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderInstallmentController::edit
     * @return void
     */
    public function testOrderInstallmentEdit(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.edit', ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderInstallmentController::create
     * @return void
     */
    public function testOrderInstallmentCreate(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.create', ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderInstallmentController::resync
     * @return void
     */
    public function testOrderInstallmentResync(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.resync', ['order' => $orderInstallment->order,], 302);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderInstallmentController::destroy
     * @return void
     */
    public function testOrderCustomerAdjustmentDelete(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForDeleteRoute($this->class, 'update', 'order-installments.delete',
            ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment], $orderInstallment);
    }
}
