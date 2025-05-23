<?php

namespace Tests\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use Illuminate\Support\Carbon;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsAccommodation;

class AccommodationInventoryRepositoryTest extends DatabaseTestCase
{
    use TestsAccommodation;

    public function testCompareTwo(): void
    {
        $a = $this->generateInventoryForComparison();
        $b = $this->generateInventoryForComparison();
        // Has start and end on same day, so equal
        $this->assertEquals(0, AccommodationInventoryRepository::compareTwo($a, $b));

        $a = $this->generateInventoryForComparison();
        $b = $this->generateInventoryForComparison(null, now()->addDay());
        // B has later end date, so b is after
        $this->assertEquals(-1, AccommodationInventoryRepository::compareTwo($a, $b));

        $a = $this->generateInventoryForComparison();
        $b = $this->generateInventoryForComparison(null, now()->subDay());
        // B has earlier end date, so b is before
        $this->assertEquals(1, AccommodationInventoryRepository::compareTwo($a, $b));

        $a = $this->generateInventoryForComparison();
        $b = $this->generateInventoryForComparison(now()->addDay());
        // B has later start date, so b is after
        $this->assertEquals(-1, AccommodationInventoryRepository::compareTwo($a, $b));

        $a = $this->generateInventoryForComparison();
        $b = $this->generateInventoryForComparison(now()->subDay());
        // B has earlier start date, so b is before
        $this->assertEquals(1, AccommodationInventoryRepository::compareTwo($a, $b));

        $a = $this->generateInventoryForComparison(now()->addDay());
        $b = $this->generateInventoryForComparison(null, now()->addDay());
        // B has earlier start date, so b is before, even if A ends earlier
        $this->assertEquals(1, AccommodationInventoryRepository::compareTwo($a, $b));
    }

    private function generateInventoryForComparison(Carbon|null $start = null, Carbon|null $end = null): AccommodationInventory
    {
        $start = $start ?? now();
        $end = $end ?? now();
        return $this->generateAccommodationInventory(null, null, null, [
            'check_in' => $start,
            'check_out' => $end,
        ]);
    }
}
