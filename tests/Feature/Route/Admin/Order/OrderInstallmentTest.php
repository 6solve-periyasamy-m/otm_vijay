<?php

namespace Route\Admin\Order;

use App\Models\Order\Order;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

class OrderInstallmentTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Order::class;

    /**
     * @covers \App\Http\Controllers\Models\OrderInstallmentController::edit
     * @return void
     */
    public function testOrderInstallmentEdit(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.edit', ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderInstallmentController::create
     * @return void
     */
    public function testOrderInstallmentCreate(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.create', ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderInstallmentController::resync
     * @return void
     */
    public function testOrderInstallmentResync(): void
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.resync', ['order' => $orderInstallment->order,], 302);
    }
}
