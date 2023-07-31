<?php

namespace Route\Admin\Access\Order;

use App\Models\Order\Order;
use Tests\Bases\Authentication\AuthenticatedRouteTestCase;
use Tests\Traits\Model\TestsOrder;

class OrderPagesRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Order::class;

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderController::index
     * @return void
     */
    public function testOrderList(): void
    {
        $this->performAllForRoute($this->class, 'read', 'orders.all', []);
    }

    /**
     * @covers \App\Http\Controllers\OrderController::show
     * @return void
     */
    public function testOrderView(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'read', 'orders.view', ['order' => $orderCustomer->order,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderController::edit
     * @return void
     */
    public function testOrderEdit(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'orders.edit', ['order' => $orderCustomer->order,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderController::create
     * @return void
     */
    public function testOrderCreate(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'create', 'orders.create', ['order' => $orderCustomer->order,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderController::occupancy
     * @return void
     */
    public function testOrderOccupancy(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'orders.occupancy', ['order' => $orderCustomer->order,]);
    }
}
