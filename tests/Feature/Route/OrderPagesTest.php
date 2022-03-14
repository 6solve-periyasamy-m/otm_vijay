<?php

namespace Route;

use App\Models\Order;
use Tests\AuthenticatedRouteTestCase;

class OrderPagesTest extends AuthenticatedRouteTestCase
{
    private string $class = Order::class;

    public function test_order_list()
    {
        $this->performAllForRoute($this->class, 'read', 'orders.all', []);
    }
}
