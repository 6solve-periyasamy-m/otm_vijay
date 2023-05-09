<?php

namespace Route\Admin\Access\Order;

use App\Models\Order\Adjustment\ManualAdjustment;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsOrder;

class OrderManualAdjustmentRouteTest extends AuthenticatedRouteTestCase
{
    use TestsOrder;

    private string $class = ManualAdjustment::class;

    /**
     * @covers \App\Http\Controllers\Models\ManualAdjustmentController::edit
     * @return void
     */
    public function testManualAdjustmentEdit(): void
    {
        $orderInstallment = $this->generateManualAdjustment(100);
        $this->performAllForRoute($this->class, 'update', 'manual-adjustments.edit', ['order' => $orderInstallment->order, 'manualAdjustment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\ManualAdjustmentController::create
     * @return void
     */
    public function testManualAdjustmentCreate(): void
    {
        $orderInstallment = $this->generateManualAdjustment(100);
        $this->performAllForRoute($this->class, 'create', 'manual-adjustments.create', ['order' => $orderInstallment->order, 'manualAdjustment' => $orderInstallment,]);
    }

    /**
     * @covers \App\Http\Controllers\Models\ManualAdjustmentController::destroy
     * @return void
     */
    public function testOrderCustomerAdjustmentDelete(): void
    {
        $orderInstallment = $this->generateManualAdjustment(100);
        $this->performAllForDeleteRoute($this->class, 'delete', 'manual-adjustments.delete',
            ['order' => $orderInstallment->order, 'manualAdjustment' => $orderInstallment], $orderInstallment);
    }
}
