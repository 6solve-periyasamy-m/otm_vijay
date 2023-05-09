<?php

namespace Tests\Bases\Authentication;

use App\Models\Helper\PermissionSet;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\Traits\Model\ComparesWithModel;

abstract class AuthenticatedFormTestCase extends AuthenticationTestCase
{
    use ComparesWithModel;

    public function performFailCases(string $route, PermissionSet $set, array $validation = [])
    {
        $this->performLoggedOut($route, [])->assertUnauthorized(); // Testing as JSON Route (would redirect normally
        $this->performAsGuest($route, [])->assertForbidden();
        $valid = $this->performWithPermission($route, $set, [])->assertStatus(422);
        if (!empty($validation)) {
            $valid->assertJsonValidationErrors($validation);
        }
    }

    public function performLoggedOut(string $url, array $json): TestResponse
    {
        return $this->performRequest(null, $url, $json);
    }

    public function performAsGuest(string $url, array $json): TestResponse
    {
        return $this->performRequest($this->guest(), $url, $json);
    }

    public function performWithPermission(string $url, PermissionSet $set, array $json): TestResponse
    {
        return $this->performRequest($this->userWithPermission($set->class, $set->action), $url, $json);
    }

    public function performWithEverything(string $url, array $json): TestResponse
    {
        return $this->performRequest($this->user(), $url, $json);
    }

    private function performRequest(User|null $user, string $url, array $json): TestResponse
    {
        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json',];
        if (!empty($user)) {
            return $this->actingAs($user)->withHeaders($headers)->postJson($url, $json);
        } else {
            return $this->withHeaders($headers)->postJson($url, $json);
        }
    }
}
