<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccommodationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_an_accommodation_can_be_created()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            //ID
            //LOCATION
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/admin/accommodation', $attributes);

        $this->assertDatabaseHas('accommodations', $attributes);
    }
}
