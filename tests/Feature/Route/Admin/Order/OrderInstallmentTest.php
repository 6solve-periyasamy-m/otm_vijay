<?php

namespace Route\Admin\Order;

use App\Models\Order\Order;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Http\Controllers\Models\OrderController::index
 * @covers \App\Http\Controllers\Models\OrderController::create
 * @covers \App\Http\Controllers\Models\OrderController::edit
 * @covers \App\Http\Controllers\Models\OrderController::show
 */
class OrderInstallmentTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Order::class;

    public function testOrderInstallmentEdit()
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.edit', ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment,]);
    }

    public function testOrderInstallmentCreate()
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.create', ['order' => $orderInstallment->order, 'orderInstallment' => $orderInstallment,]);
    }

    public function testOrderInstallmentResync()
    {
        $orderInstallment = $this->generateOrderInstallment(now(), 100);
        $this->performAllForRoute($this->class, 'update', 'order-installments.resync', ['order' => $orderInstallment->order,], 302);
    }
}
