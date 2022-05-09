<?php

namespace Tests;

use App\Models\User;
use Illuminate\Testing\TestResponse;

class RouteTestCase extends TestCase
{
    protected function performRouteRequest(string $route, array $params = []): TestResponse
    {
        return $this->get(route($route, $params));
    }
}
