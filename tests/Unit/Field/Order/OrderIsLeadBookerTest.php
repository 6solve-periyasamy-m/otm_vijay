<?php

namespace Field\Order;

use App\Models\Customer\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Repository\Model\Order\OrderRepository::isLeadBooker
 */
class OrderIsLeadBookerTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    public function testIsLeadBookerWithLead()
    {
        $order = $this->generateOrder();
        $this->assertTrue($order->repository->isLeadBooker($order->leadBooker->customer));
    }

    public function testIsLeadBookerWithAdditional()
    {
        $order = $this->generateOrder();
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $this->assertFalse($order->repository->isLeadBooker($orderCustomer->customer));
    }

    public function testLeadIsLeadBookerWithAdditional()
    {
        $order = $this->generateOrder();
        $this->generateOrderCustomer(false, $order);
        $this->assertTrue($order->repository->isLeadBooker($order->leadBooker->customer));
    }

    public function testWithNotCustomer()
    {
        $customer = Customer::factory()->create();
        $order = $this->generateOrder();
        $this->assertFalse($order->repository->isLeadBooker($customer));
    }

}
