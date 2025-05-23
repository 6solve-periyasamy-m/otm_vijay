<?php

namespace Route\Admin\Access\Order;

use App\Models\Order\OrderCustomer;
use Tests\Bases\Authentication\AuthenticatedRouteTestCase;
use Tests\Traits\Model\TestsOrder;

class OrderCustomerRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = OrderCustomer::class;

    /**
     * @covers \App\Http\Controllers\OrderCustomerController::show
     * @return void
     */
    public function testOrderCustomerView(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'read', 'order-customers.view', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderCustomerController::edit
     * @return void
     */
    public function testOrderCustomerEdit(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'order-customers.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderCustomerController::create
     * @return void
     */
    public function testOrderCustomerCreate(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'create', 'order-customers.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }
}
