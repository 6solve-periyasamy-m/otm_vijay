<?php

namespace Field\Order;

use App\Models\Customer\Customer;
use App\Repository\StaticOrderRepository;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Repository\StaticOrderRepository::isOrderCustomer
 */
class OrderIsCustomerTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testIsCustomerWithLead()
    {
        $order = $this->generateOrder();
        $this->assertTrue(StaticOrderRepository::isOrderCustomer($order, $order->leadBooker->customer));
    }

    public function testIsCustomerWithAdditional()
    {
        $order = $this->generateOrder();
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $this->assertTrue(StaticOrderRepository::isOrderCustomer($order, $orderCustomer->customer));
    }

    public function testLeadIsCustomerWithAdditional()
    {
        $order = $this->generateOrder();
        $orderCustomer = $this->generateOrderCustomer(false, $order);
        $this->assertTrue(StaticOrderRepository::isOrderCustomer($order, $order->leadBooker->customer));
    }

    public function testWithNotCustomer()
    {
        $customer = Customer::factory()->create();
        $order = $this->generateOrder();
        $this->assertFalse(StaticOrderRepository::isOrderCustomer($order, $customer));
    }

}
