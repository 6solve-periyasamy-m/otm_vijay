<?php

namespace Helper;

use Tests\Bases\DatabaseTestCase;

class RoundToNearestTest extends DatabaseTestCase
{
    public function testRoundToNearest()
    {
        // Expected functionality: Always rounds up to closest X value
        $this->assertEquals(5.0, round_to_nearest(4.9, 1));
        $this->assertEquals(5.0, round_to_nearest(4.9, 0.5));
        $this->assertEquals(5.0, round_to_nearest(4.9, 5));
        $this->assertEquals(10.0, round_to_nearest(4.9, 10));
        $this->assertEquals(5.0, round_to_nearest(4.1, 1));
        $this->assertEquals(4.5, round_to_nearest(4.1, 0.5));
    }
}