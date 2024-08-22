<?php

namespace Tests\Unit\Method\Repository\Costing\Tour;

use App\Repository\Costing\Tour\TourCostingRepository;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsTour;

/**
 * @covers \App\Repository\Costing\Tour\TourCostingRepository
 */
class TourCostingRepositoryTest extends DatabaseTestCase
{
    use TestsTour;

    /**
     * @covers \App\Repository\Costing\Tour\TourCostingRepository::getBaseMargin
     * @return void
     */
    public function testGetBaseMargin(): void
    {
        $tour = $this->generateTour(true);

        // Test if cost is not 0
        $cost = 0;
        foreach ($tour->repository->getComponents() as $component) {
            if ($component->getTourComponentType() === 'Included') {
                $inventory = $component->getInventory()->get();
                $inventory->purchase_price = 100;
                $inventory->save();
                $cost += $component->getLocalPurchasePrice();
            }
        }
        $this->assertEquals(($tour->base_price_per_person / $cost) * 100, $tour->repository->getCosting()->getBaseMargin());

        // Test if cost is 0
        foreach ($tour->repository->getComponents() as $component) {
            if ($component->getTourComponentType() === 'Included') {
                $inventory = $component->getInventory()->get();
                $inventory->purchase_price = 0;
                $inventory->save();
            }
        }
        // 100% Profit if cost is 0
        $this->assertEquals(100, $tour->repository->getCosting()->getBaseMargin());
    }
}
