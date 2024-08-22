<?php

namespace Tests\Unit\Method\Repository\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsTour;


/**
 * @covers \App\Repository\Model\Accommodation\AccommodationInventoryRepository::getBetweenDates
 */
class GetBetweenDatesTest extends DatabaseTestCase
{
    use TestsTour;

    private function wipe(): void
    {
        AccommodationInventory::all()->each(function ($inventory) { $inventory->delete(); });
    }

    public function testWithOnlyOne()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(3), 'check_out' => now()->addDays(3)]);
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }

    public function testWithTwo()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(3), 'check_out' => now()->addDays(3)]);
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(5), 'check_out' => now()->addDays(5)]);
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10));
        $this->assertEquals(2, $between->count());
    }

    public function testWithOneOutsideRange()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(3), 'check_out' => now()->addDays(3)]);
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(11), 'check_out' => now()->addDays(11)]);
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }

    public function testWithOneAtStartAndEnd()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(10), 'check_out' => now()->addDays(9)]);
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10));
        $this->assertEquals(1, $between->count());
    }

    public function testWithNone()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(11), 'check_out' => now()->addDays(11)]);
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10));
        $this->assertEquals(0, $between->count());
    }

    public function testWithOneInTour()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $inventory = $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(5), 'check_out' => now()->addDays(5)]);
        $tour = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory)->tour;
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10), $tour->repository);
        $this->assertEquals(0, $between->count());
    }

    public function testWithOneInTourAndOneOut()
    {
        $this->wipe();
        $accommodation = $this->generateAccommodation();
        $inventory = $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(5), 'check_out' => now()->addDays(5)]);
        $this->generateAccommodationInventory($accommodation, null, null, ['check_in' => now()->subDays(4), 'check_out' => now()->addDays(6)]);
        $tour = $this->generateAccommodationInventoryTour(null, 'Included', 100, $inventory)->tour;
        $between = AccommodationInventoryRepository::getBetweenDates(now()->subDays(10), now()->addDays(10), $tour->repository);
        $this->assertEquals(1, $between->count());
    }
}
