<?php

namespace Tests\suspended_features\suspended_24_05_2022;

use Tests\TestCase;

class BookingRetrieveTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function getBookingForm()
    {
        $response = $this->get('/booking');

        $response->assertStatus(200);
    }
}
