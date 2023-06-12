<?php

namespace Field\Voucher\Executors;

use App\Models\Voucher\Executors\FreeComponentExecutor;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;
use Tests\Traits\TestsVoucher;

/**
 * @covers \App\Models\Voucher\Executors\FreeComponentExecutor
 */
class FreeComponentExecutorTest extends DatabaseTestCase
{
    use TestsVoucher;
    use TestsOrder;

    public function testActivityOnSingleTraveller()
    {
        $order = $this->generateOrder();
        $component = $this->generateActivityInventoryTour($order->tour);
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(FreeComponentExecutor::create($component->repository));
        $this->assertNull($component->repository->getOrderComponent($order->leadBooker));
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $this->assertNotNull($component->repository->getOrderComponent($order->leadBooker));
    }

    public function testFlightOnSingleTraveller()
    {
        $order = $this->generateOrder();
        $component = $this->generateFlightInventoryTour($order->tour);
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(FreeComponentExecutor::create($component->repository));
        $this->assertNull($component->repository->getOrderComponent($order->leadBooker));
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $this->assertNotNull($component->repository->getOrderComponent($order->leadBooker));
    }

    public function testTransportOnSingleTraveller()
    {
        $order = $this->generateOrder();
        $component = $this->generateTransportInventoryTour($order->tour);
        $voucher = $this->generateVoucher();
        $result = $voucher->results()->save(FreeComponentExecutor::create($component->repository));
        $this->assertNull($component->repository->getOrderComponent($order->leadBooker));
        $result->executor()->applyForOrderCustomer($order->leadBooker);
        $this->assertNotNull($component->repository->getOrderComponent($order->leadBooker));
    }


}
