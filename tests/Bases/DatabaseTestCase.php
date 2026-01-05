<?php

namespace Tests\Bases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Settings;

abstract class DatabaseTestCase extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Settings::forceRenewal();
        Settings::set('system.currency', 'GBP');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        gc_collect_cycles();
    }
}
