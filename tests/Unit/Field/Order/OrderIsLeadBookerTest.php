<?php

namespace Field\Order;

use App\Models\Customer\Customer;
use App\Repository\StaticOrderRepository;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Repository\StaticOrderRepository::isLeadBooker
 */
class OrderIsLeadBookerTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testIsLeadBookerWithLead()
    {
        $order = $this->generateOrder();
        $this->assertTrue(StaticOrderRepository::isLeadBooker($order, $order->leadBooker->customer));
    }

    public function testIsLeadBookerWithAdditional()
    {
        $order = $this->generateOrder();
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $this->assertFalse(StaticOrderRepository::isLeadBooker($order, $orderCustomer->customer));
    }

    public function testLeadIsLeadBookerWithAdditional()
    {
        $order = $this->generateOrder();
        $this->generateOrderCustomer(false, $order);
        $this->assertTrue(StaticOrderRepository::isLeadBooker($order, $order->leadBooker->customer));
    }

    public function testWithNotCustomer()
    {
        $customer = Customer::factory()->create();
        $order = $this->generateOrder();
        $this->assertFalse(StaticOrderRepository::isLeadBooker($order, $customer));
    }

}
