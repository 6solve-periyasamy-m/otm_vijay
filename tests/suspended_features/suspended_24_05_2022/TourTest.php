<?php

namespace Tests\suspended_features\suspended_24_05_2022;

use Tests\TestCase;

class TourTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
