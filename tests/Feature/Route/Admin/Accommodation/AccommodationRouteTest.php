<?php

namespace Route\Admin\Accommodation;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use Tests\AuthenticatedRouteTestCase;
use Tests\Traits\TestsAccommodation;

class AccommodationRouteTest extends AuthenticatedRouteTestCase
{
    use TestsAccommodation;

    private string $class = Accommodation::class;

    /**
     * @covers \App\Http\Controllers\Admin\Accommodation\AccommodationController::index
     * @return void
     */
    public function testAccommodationList(): void
    {
        Accommodation::factory(4)->create();
        $this->performAllForRoute($this->class, 'read', 'accommodations.all', []);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Accommodation\AccommodationController::view
     * @return void
     */
    public function testAccommodationView(): void
    {
        $accommodation = $this->generateAccommodation();
        $accommodation->inventory()->saveMany(AccommodationInventory::factory(5)->make());
        $this->performAllForRoute($this->class, 'read', 'accommodations.view', ['accommodation' => $accommodation,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Accommodation\AccommodationController::edit
     * @return void
     */
    public function testAccommodationEdit(): void
    {
        $accommodation = $this->generateAccommodation();
        $this->performAllForRoute($this->class, 'update', 'accommodations.edit', ['accommodation' => $accommodation,]);
    }

    /**
     * @covers \App\Http\Controllers\Admin\Accommodation\AccommodationController::create
     * @return void
     */
    public function testAccommodationCreate(): void
    {
        $this->performAllForRoute($this->class, 'create', 'accommodations.create');
    }

    /**
     * @covers \App\Http\Controllers\Admin\Accommodation\AccommodationController::destroy
     * @return void
     */
    public function testAccommodationDelete(): void
    {
        $accommodation = $this->generateAccommodation();
        $this->performAllForDeleteRoute($this->class, 'delete', 'accommodations.delete', ['accommodation' => $accommodation,], $accommodation);
    }
}
