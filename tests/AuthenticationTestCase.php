<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\TestsAuthentication;

abstract class AuthenticationTestCase extends DatabaseTestCase
{
    use TestsAuthentication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAuthenticationRoles();
    }
}
