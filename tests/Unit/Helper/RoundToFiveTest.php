<?php

namespace Helper;

use Tests\Bases\TestCase;

class RoundToFiveTest extends TestCase
{
    public function testRoundToFive(): void
    {
        $this->assertEquals(10, round_to_five(10));
        $this->assertEquals(15, round_to_five(15));
        $this->assertEquals(15, round_to_five(10.10));
        $this->assertEquals(15, round_to_five(14.99));
    }
}