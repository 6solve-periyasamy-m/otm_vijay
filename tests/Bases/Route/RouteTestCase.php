<?php

namespace Tests\Bases\Route;

use Illuminate\Testing\TestResponse;
use Tests\Bases\TestCase;

abstract class RouteTestCase extends TestCase
{
    protected function performRouteRequest(string $route, array $params = []): TestResponse
    {
        return $this->get(route($route, $params));
    }
}
