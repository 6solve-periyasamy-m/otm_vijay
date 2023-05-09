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
     * @covers \App\Http\Controllers\Models\OrderCustomerModelController::edit
     * @return void
     */
    public function testOrderCustomerEdit(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'order-customers.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderCustomerModelController::create
     * @return void
     */
    public function testOrderCustomerCreate(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'create', 'order-customers.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderCustomerModelController::destroy
     * @return void
     */
    public function testOrderCustomerAdjustmentDelete(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForDeleteRoute($this->class, 'delete', 'order-customers.delete',
            ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ], $orderCustomer);
    }
}
