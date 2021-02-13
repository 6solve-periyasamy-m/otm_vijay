<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
