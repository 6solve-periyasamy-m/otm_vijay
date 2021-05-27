<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Accommodation;

class AccommodationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_accommodation_for_tours()
    {
        $attributes = [
            'tour_id' => 1
        ];
        Accommodation::factory()->create($attributes);
        $accommodation = new Accommodation();
        $forTour = $accommodation->getAccommodationForTour();

        //$this->assertDatabaseCount();
    }

    public function test_an_accommodation_can_be_created()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            //ID
            //LOCATION
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'region_id' => 1,
        ];

        //$this->post('/admin/accommodation', $attributes);
        Accommodation::factory()->create($attributes);

        $this->assertDatabaseHas('accommodations', $attributes);
    }
}
