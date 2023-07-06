<?php

namespace Tests\Bases\Authentication;

use Tests\Bases\DatabaseTestCase;
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
