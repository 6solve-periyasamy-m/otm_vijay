<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Flight;

use Tests\TestCase;

class BookingTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    /**
     * test a booking form will load 
     *
     * @return void
     */
    public function test_booking_form_can_be_accessed()
    {
        $response = $this->get('/booking');

        $response->assertStatus(200);
    }

    /**
     * A test a specific booking form can be accessed by URL
     *
     * @return void
     */
    public function test_specific_booking_can_be_accessed()
    {
        $response = $this->get('/booking/tour/example-tour');

        $response->assertStatus(200);
    }

    /**
     * test a flight can be accessed by ID
     *
     * @return void
     */
    public function test_specific_booking_has_flights()
    {
        $flight = Flight::factory('App\Flight')->make();
\Log::debug('flight', $flight->toArray());

        $response = $this->getJson('/api/booking/flights/1');

        $response->assertStatus(200);
    }


    // public function test_tour_creation()
    // {
    //     $this->withoutExceptionHandling();

    //     $attributes = [
    //         //ID
    //         //LOCATION
    //         'title' => $this->faker->sentence,
    //         'description' => $this->faker->paragraph
    //     ];

    //     $this->put('/admin/tours/store', $attributes);

    //     $this->assertDatabaseHas('tours', $attributes);
    // }
}
