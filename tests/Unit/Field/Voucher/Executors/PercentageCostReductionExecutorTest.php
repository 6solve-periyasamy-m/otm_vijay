<?php

namespace Field\Voucher\Executors;

use App\Models\Voucher\Executors\PercentageCostReductionExecutor;
use App\Models\Voucher\VoucherCodeResult;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;
use Tests\Traits\TestsVoucher;

class PercentageCostReductionExecutorTest extends DatabaseTestCase
{
    use TestsOrder;
    use TestsVoucher;

    public function testCostReductionWithSingleTraveller()
    {
        $order = $this->generateOrder();
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(PercentageCostReductionExecutor::create(10));
        $this->assertIsNotBool($result, 'Executor failed to save');
        /** @var VoucherCodeResult $result */
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $cost = $order->tour->base_price_per_person;
        $this->assertEquals(sigfig($order->cost - ($cost/10)), $order->total);
    }

    public function testCostReductionWithMultipleTravellers()
    {
        $order = $this->generateOrder();
        $this->generateOrderCustomer(false, $order);
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(PercentageCostReductionExecutor::create(10));
        $this->assertIsNotBool($result, 'Executor failed to save');
        /** @var VoucherCodeResult $result */
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $cost = $order->tour->base_price_per_person * 2;
        $this->assertEquals(sigfig($order->cost - ($cost/10)), $order->total);
    }

    public function testCostReductionWithSingleTravellerSingleOccupancy()
    {
        $order = $this->generateOrder();
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(PercentageCostReductionExecutor::create(10));
        $this->assertIsNotBool($result, 'Executor failed to save');
        /** @var VoucherCodeResult $result */
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $cost = $order->tour->base_price_per_person;
        $this->assertEquals(sigfig($order->cost - ($cost/10)), $order->total);
    }

    public function testCostReductionWithMultipleTravellersSingleOccupancy()
    {
        $order = $this->generateOrder();
        $this->generateOrderCustomer(false, $order);
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(PercentageCostReductionExecutor::create(10));
        $this->assertIsNotBool($result, 'Executor failed to save');
        /** @var VoucherCodeResult $result */
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $cost = $order->tour->base_price_per_person * 2;
        $this->assertEquals(sigfig($order->cost - ($cost/10)), $order->total);
    }
}
