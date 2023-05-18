<?php

namespace Route\Admin\Order;

use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

class OrderCustomerAdjustmentRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = OrderCustomerAdjustment::class;

    /**
     * @covers \App\Http\Controllers\Admin\Order\Adjustment\OrderCustomerAdjustmentController::edit
     * @return void
     */
    public function testOrderCustomerAdjustmentEdit(): void
    {
        $orderInstallment = $this->generateOrderCustomerAdjustment(100);
        $this->performAllForRoute($this->class, 'update', 'order-customer-adjustments.edit',
            ['order' => $orderInstallment->orderCustomer->order, 'orderCustomer' => $orderInstallment->orderCustomer, 'orderCustomerAdjustment' => $orderInstallment]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\Adjustment\OrderCustomerAdjustmentController::create
     * @return void
     */
    public function testOrderCustomerAdjustmentCreate(): void
    {
        $orderInstallment = $this->generateOrderCustomerAdjustment(100);
        $this->performAllForRoute($this->class, 'create', 'order-customer-adjustments.create',
            ['order' => $orderInstallment->orderCustomer->order, 'orderCustomer' => $orderInstallment->orderCustomer, 'orderCustomerAdjustment' => $orderInstallment]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\Adjustment\OrderCustomerAdjustmentController::destroy
     * @return void
     */
    public function testOrderCustomerAdjustmentDelete(): void
    {
        $orderInstallment = $this->generateOrderCustomerAdjustment(100);
        $this->performAllForDeleteRoute($this->class, 'delete', 'order-customer-adjustments.delete',
            ['order' => $orderInstallment->orderCustomer->order, 'orderCustomer' => $orderInstallment->orderCustomer, 'orderCustomerAdjustment' => $orderInstallment], $orderInstallment);
    }
}
