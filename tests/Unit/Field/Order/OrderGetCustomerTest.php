<?php

namespace Field\Order;

use App\Models\Customer\Customer;
use App\Repository\StaticOrderRepository;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;


/**
 * @covers \App\Repository\Model\Order\OrderRepository::getOrderCustomer
 */
class OrderGetCustomerTest extends DatabaseTestCase
{
    use TestsOrder;

    /*
     * NB: Comparing the models directly leads to failure
     */

    public function testGetCustomerWithLead()
    {
        $order = $this->generateOrder();
        $this->assertEquals($order->leadBooker->id, $order->repository->getOrderCustomer($order->leadBooker->customer)->id);
    }

    public function testGetCustomerWithAdditional()
    {
        $order = $this->generateOrder();
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $this->assertEquals($orderCustomer->id, $order->repository->getOrderCustomer($orderCustomer->customer)->id);
    }

    public function testLeadGetCustomerWithAdditional()
    {
        $order = $this->generateOrder();
        $this->generateOrderCustomer(false, $order);
        $this->assertEquals($order->leadBooker->id, $order->repository->getOrderCustomer($order->leadBooker->customer)->id);
    }

    public function testWithNotCustomer()
    {
        $customer = Customer::factory()->create();
        $order = $this->generateOrder();
        $this->assertNull($order->repository->getOrderCustomer($customer));
    }

}
