<?php

namespace Route\Customer;

use Tests\Bases\Route\CustomerRouteTestCase;

class CustomerPortalTest extends CustomerRouteTestCase
{
    /**
     * @covers \App\Http\Controllers\Customer\CustomerPortalController::show
     * @return void
     */
    public function testCustomerPortal()
    {
        $this->performAllForRoute('customer.portal');
    }

    /**
     * @covers \App\Http\Controllers\Customer\CustomerFinancesController::show
     * @return void
     */
    public function testCustomerFinances()
    {
        $this->performAllForRoute('customer.finances');
    }

    /**
     * @covers \App\Http\Controllers\Customer\CustomerTourController::showItinerary
     * @return void
     */
    public function testCustomerItinerary()
    {
        $this->performAllForRoute('customer.itinerary');
    }

    /**
     * @covers \App\Http\Controllers\Customer\CustomerTourController::showExtras
     * @return void
     */
    public function testCustomerExtras()
    {
        $this->performAllForRoute('customer.extras');
    }
}
