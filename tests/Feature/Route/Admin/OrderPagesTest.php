<?php

namespace Route\Admin;

use App\Models\Order\Order;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Http\Controllers\Models\OrderController::index
 * @covers \App\Http\Controllers\Models\OrderController::create
 * @covers \App\Http\Controllers\Models\OrderController::edit
 * @covers \App\Http\Controllers\Models\OrderController::show
 */
class OrderPagesTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Order::class;

    public function testOrderList()
    {
        $this->performAllForRoute($this->class, 'read', 'orders.all', []);
    }

    public function testOrderView()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'read', 'orders.view', ['order' => $orderCustomer->order,]);
    }

    public function testOrderEdit()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'orders.edit', ['order' => $orderCustomer->order,]);
    }

    public function testOrderCreate()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'create', 'orders.create', ['order' => $orderCustomer->order,]);
    }
}
