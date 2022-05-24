<?php

namespace Route\Admin\Order;

use App\Models\Order\Order;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

class OrderPagesTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = Order::class;

    /**
     * @covers \App\Http\Controllers\Models\OrderController::index
     * @return void
     */
    public function testOrderList(): void
    {
        $this->performAllForRoute($this->class, 'read', 'orders.all', []);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderController::show
     * @return void
     */
    public function testOrderView(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'read', 'orders.view', ['order' => $orderCustomer->order,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderController::edit
     * @return void
     */
    public function testOrderEdit(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'orders.edit', ['order' => $orderCustomer->order,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderController::create
     * @return void
     */
    public function testOrderCreate(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'create', 'orders.create', ['order' => $orderCustomer->order,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\OrderController::occupancy
     * @return void
     */
    public function testOrderOccupancy(): void
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->performAllForRoute($this->class, 'update', 'orders.occupancy', ['order' => $orderCustomer->order,]);
    }
}
