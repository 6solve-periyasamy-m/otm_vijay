<?php

namespace Tests;

use App\Models\Customer\Customer;
use Illuminate\Testing\TestResponse;
use Tests\Traits\TestsOrder;

abstract class CustomerRouteTestCase extends AuthenticationTestCase
{
    use TestsOrder;

    private function getCustomer(): Customer
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        return $orderCustomer->customer;
    }

    private function performRouteRequestAs(Customer $user, string $route, array $params = []): TestResponse
    {
        return $this->actingAs($user, 'customer')->get(route($route, $params));
    }

    public function performAllForRoute(string $route, array $params = [])
    {
        print_r('Testing logged out on ' . $route . "\n");
        $this->performRouteLoggedOut($route, $params);
        print_r('Testing logged in on ' . $route . "\n");
        $this->performRouteWithEverything($route, $params);
    }


    public function performRouteLoggedOut(string $route, array $params = [])
    {
        $this->get(route($route, $params))->assertStatus(302); // Should redirect to login screen
    }

    public function performRouteWithEverything(string $route, array $params = [])
    {
        $this->performRouteRequestAs($this->getCustomer(), $route, $params)->assertStatus(200);
    }

}
