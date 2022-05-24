<?php

namespace Route\Admin\Order;

use App\Models\Order\OrderCustomer;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Http\Controllers\Models\OrderController::create
 * @covers \App\Http\Controllers\Models\OrderController::edit
 * @covers \App\Http\Controllers\Models\OrderController::show
 */
class OrderCustomerTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = OrderCustomer::class;

    public function testOrderCustomerView()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'read', 'order-customers.view', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }

    public function testOrderCustomerEdit()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'order-customers.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }

    public function testOrderCustomerCreate()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'create', 'order-customers.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }
}
