<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccommodationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    // this test does not work, as dependencies in Voyager are not respected
    public function test_an_accommodation_can_be_created()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            //ID
            //LOCATION
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'region_id' => 1
        ];

        $this->post('/admin/accommodation', $attributes);

        $this->assertDatabaseHas('accommodations', $attributes);
    }
}
