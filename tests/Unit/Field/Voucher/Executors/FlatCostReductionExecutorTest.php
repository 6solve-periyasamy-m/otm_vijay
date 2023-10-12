<?php

namespace Field\Voucher\Executors;

use App\Models\Voucher\Executors\FlatCostReductionExecutor;
use App\Models\Voucher\VoucherCodeResult;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsVoucher;

class FlatCostReductionExecutorTest extends DatabaseTestCase
{
    use TestsOrder;
    use TestsVoucher;

    public function testCostReductionWithSingleTraveller()
    {
        $order = $this->generateOrder();
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(FlatCostReductionExecutor::create(-100));
        $this->assertIsNotBool($result, 'Executor failed to save');
        /** @var VoucherCodeResult $result */
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $this->assertEquals($this->getDefaultCost($order) - 100, $order->total);
    }

    public function testCostReductionWithMultipleTravellers()
    {
        $order = $this->generateOrder();
        $this->generateOrderCustomer(false, $order);
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(FlatCostReductionExecutor::create(-100));
        $this->assertIsNotBool($result, 'Executor failed to save');
        /** @var VoucherCodeResult $result */
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $this->assertEquals($this->getDefaultCost($order) - 100, $order->total);
    }
}
