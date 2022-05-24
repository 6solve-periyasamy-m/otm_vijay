<?php

namespace Method\Repository\Accommodation;

use App\Repository\Model\Accommodation\AccommodationInventoryTourRepository;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsTour;


/**
 * @covers \App\Repository\Model\Accommodation\AccommodationInventoryTourRepository::getBetweenDates
 */
class GetBetweenDatesTest extends DatabaseTestCase
{
    use TestsTour;

    public function testWithOnlyOne()
    {
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(3), 'check_out' => now()->addDays(3)]);
        $between = AccommodationInventoryTourRepository::getBetweenDates($this->generateTour(), now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }

    public function testWithTwo()
    {
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(3), 'check_out' => now()->addDays(3)]);
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(5), 'check_out' => now()->addDays(5)]);
        $between = AccommodationInventoryTourRepository::getBetweenDates($this->generateTour(), now()->subDays(10), now()->addDays(10));
        $this->assertEquals(2, $between->count());
    }

    public function testWithOneOutsideRange()
    {
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(3), 'check_out' => now()->addDays(3)]);
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(11), 'check_out' => now()->addDays(11)]);
        $between = AccommodationInventoryTourRepository::getBetweenDates($this->generateTour(), now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }

    public function testWithOneAtStartAndEnd()
    {
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(10), 'check_out' => now()->addDays(10)]);
        $between = AccommodationInventoryTourRepository::getBetweenDates($this->generateTour(), now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }

    public function testWithNone()
    {
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(11), 'check_out' => now()->addDays(11)]);
        $between = AccommodationInventoryTourRepository::getBetweenDates($this->generateTour(), now()->subDays(10), now()->addDays(10));
        $this->assertEquals(0, $between->count());
    }

    public function testWithOneInTour()
    {
        $accommodation = $this->generateAccommodation();
        $inventory = $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(5), 'check_out' => now()->addDays(5)]);
        $tour = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory)->tour;
        $between = AccommodationInventoryTourRepository::getBetweenDates($tour, now()->subDays(10), now()->addDays(10));
        $this->assertEquals(0, $between->count());
    }

    public function testWithOneInTourAndOneOut()
    {
        $accommodation = $this->generateAccommodation();
        $inventory = $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(5), 'check_out' => now()->addDays(5)]);
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(4), 'check_out' => now()->addDays(6)]);
        $tour = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory)->tour;
        $between = AccommodationInventoryTourRepository::getBetweenDates($tour, now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }
}