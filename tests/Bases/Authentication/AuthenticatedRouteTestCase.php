<?php /** @noinspection LaravelFunctionsInspection */

namespace Tests\Bases\Authentication;

use App\Models\Order\OrderCustomer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\TestResponse;

abstract class AuthenticatedRouteTestCase extends AuthenticationTestCase
{
    /*
     * Shorthand Methods
     */

    /**
     * Performs a standard set of tests for a route that requires permission
     * Tests that:
     *   Logged out fails
     *   All permissions succeeds
     *   No permissions fails
     *   Singular permission succeeds
     * @param string $class The class you need permission for
     * @param string $action The action you need permission for (usually create/read/update/delete)
     * @param string $route The route name you with to test
     * @param array $params The parameters to pass to the route
     * @param int $expectedStatus The expected status code on success (200 for page showing, 302 for redirect)
     * @return void
     */
    public function performAllForRoute(string $class, string $action, string $route, array $params = [], int $expectedStatus = 200): void
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

    /**
     * Performs a standard set of tests on a model delete route
     * Tests that:
     *   Logged out fails
     *   All permissions succeeds
     *   No permissions fails
     *   Singular permission succeeds
     * @param string $class The class you need permission for
     * @param string $action The action you need permission for (usually create/read/update/delete)
     * @param string $route The route name you with to test
     * @param array $params The parameters to pass to the route
     * @param Model $model The model testing. Used to verify it was actually deleted
     * @return void
     */
    public function performAllForDeleteRoute(string $class, string $action, string $route, array $params, Model $model): void
    {
        env('SHOULD_LOG', true) && print_r('Testing logged out on ' . $route . "\n");
        $this->performPostRouteLoggedOut($route, $params);

        env('SHOULD_LOG', true) && print_r('Testing with everything on ' . $route . "\n");
        $this->performPostRouteWithEverything($route, $params, 302);
        // Requires refreshing as the model is cached, so is not deleted
        //$model->refresh();
        $model = OrderCustomer::find($model->id);
        $this->assertNull($model);
        // Restore for use later in the test
        $model->restore();

        env('SHOULD_LOG', true) && print_r('Testing without permission on ' . $route . "\n");
        $this->performPostRouteUnauthenticated($route, $params);

        env('SHOULD_LOG', true) && print_r('Testing with specific permission on ' . $route . "\n");
        $this->performPostRouteWithSpecific($class, $action, $route, $params, 302);
        $model->refresh();
        $this->assertTrue($model->trashed());
    }

    /*
     * GET Methods
     */

    private function performRouteRequestAs(User $user, string $route, array $params = []): TestResponse
    {
        return $this->actingAs($user)->get(route($route, $params));
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

    /*
     * POST Methods
     */

    private function performPostRouteRequestAs(User $user, string $route, array $params = []): TestResponse
    {
        return $this->actingAs($user)->post(route($route, $params));
    }

    public function performPostRouteLoggedOut(string $route, array $params = [])
    {
        $this->post(route($route, $params))->assertStatus(302); // Should redirect to login screen
    }

    public function performPostRouteWithEverything(string $route, array $params = [], int $expectedStatus = 200)
    {
        $this->performPostRouteRequestAs($this->user(), $route, $params)->assertStatus($expectedStatus);
    }

    public function performPostRouteWithSpecific($class, string $action, string $route, array $params = [], int $expectedStatus = 200)
    {
        $user = $this->userWithPermission($class, $action);
        $this->performPostRouteRequestAs($user, $route, $params)->assertStatus($expectedStatus);
    }

    public function performPostRouteUnauthenticated(string $route, array $params = [])
    {
        $this->performPostRouteRequestAs($this->guest(), $route, $params)->assertStatus(403);
    }
}
