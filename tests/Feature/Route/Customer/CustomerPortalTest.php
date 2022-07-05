<?php

namespace Route\Customer;

use Tests\CustomerRouteTestCase;

class CustomerPortalTest extends CustomerRouteTestCase
{
    public function testCustomerPortal()
    {
        $this->performAllForRoute('customer.portal');
    }

    public function testCustomerFinances()
    {
        $this->performAllForRoute('customer.finances');
    }

    public function testCustomerItinerary()
    {
        $this->performAllForRoute('customer.itinerary');
    }

    public function testCustomerExtras()
    {
        $this->performAllForRoute('customer.extras');
    }
}