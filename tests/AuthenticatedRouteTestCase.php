<?php /** @noinspection LaravelFunctionsInspection */

namespace Tests;

use App\Models\User;
use Illuminate\Testing\TestResponse;

abstract class AuthenticatedRouteTestCase extends AuthenticationTestCase
{

    private function performRouteRequestAs(User $user, string $route, array $params = []): TestResponse
    {
        return $this->actingAs($user)->get(route($route, $params));
    }

    public function performAllForRoute(string $class, string $action, string $route, array $params = [], int $expectedStatus = 200)
    {
        env('SHOULD_LOG', true) && print_r('Testing logged out on ' . $route . "\n");
        $this->performRouteLoggedOut($route, $params);
        env('SHOULD_LOG', true) && print_r('Testing with everything on ' . $route . "\n");
        $this->performRouteWithEverything($route, $params, $expectedStatus);
        env('SHOULD_LOG', true) && print_r('Testing without permission on ' . $route . "\n");
        $this->performRouteUnauthenticated($route, $params);
        env('SHOULD_LOG', true) && print_r('Testing with specific permission on ' . $route . "\n");
        $this->performRouteWithSpecific($class, $action, $route, $params, $expectedStatus);
    }


    public function performRouteLoggedOut(string $route, array $params = [])
    {
        $this->get(route($route, $params))->assertStatus(302); // Should redirect to login screen
    }

    public function performRouteWithEverything(string $route, array $params = [], int $expectedStatus = 200)
    {
        $this->performRouteRequestAs($this->user(), $route, $params)->assertStatus($expectedStatus);
    }

    public function performRouteWithSpecific($class, string $action, string $route, array $params = [], int $expectedStatus = 200)
    {
        $user = $this->userWithPermission($class, $action);
        $this->performRouteRequestAs($user, $route, $params)->assertStatus($expectedStatus);
    }

    public function performRouteUnauthenticated(string $route, array $params = [])
    {
        $this->performRouteRequestAs($this->guest(), $route, $params)->assertStatus(403);
    }
}
