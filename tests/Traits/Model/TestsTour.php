<?php

namespace Tests\Traits\Model;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;

trait TestsTour
{
    use TestsAccommodation;
    use TestsActivity;
    use TestsFlight;
    use TestsTransport;
    use TestsMerchandise;

    function generateTour(bool $withIncluded = true, array $data = []): Tour
    {
        $tour = Tour::factory()->create($data);
        if ($withIncluded) {
            for ($x = 0; $x < 5; $x++) {
                $this->generateAccommodationInventoryTour($tour, 'Included', 100, $this->generateAccommodationInventory(null, null, null, ['check_in' => now()->addDays($x), 'check_out' => now()->addDays($x + 5)]));
                $this->generateActivityInventoryTour($tour);
                $this->generateFlightInventoryTour($tour);
                $this->generateTransportInventoryTour($tour);
                $this->generateMerchandiseInventoryTour($tour);
            }
        }
        return $tour;
    }

    function generateMerchandiseInventoryTour(?Tour $tour = null, string $componentType = 'Included', float $cost = 100, ?MerchandiseInventory $inventory = null): MerchandiseInventoryTour
    {
        $tour = $tour ?? $this->generateTour();
        $inventory = $inventory ?? $this->generateMerchandiseInventory();
        $merchandise = MerchandiseInventoryTour::factory()->make(['tour_component_type' => $componentType, 'tour_sales_price' => $cost, 'tour_id' => $tour->id,]);
        $inventory->tourComponents()->save($merchandise);
        return $merchandise;
    }

    function generateAccommodationInventoryTour(?Tour $tour = null, string $componentType = 'Included', float $cost = 100, ?AccommodationInventory $inventory = null): AccommodationInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = $this->generateAccommodationInventory();
        $tourComponent = new AccommodationInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
            'is_template' => $componentType === 'Included',
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

    function generateActivityInventoryTour(?Tour $tour = null, string $componentType = 'Included', float $cost = 100, ?ActivityInventory $inventory = null): ActivityInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = $this->generateActivityInventory();
        $tourComponent = new ActivityInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

    function generateFlightInventoryTour(?Tour $tour = null, string $componentType = 'Included', float $cost = 100, ?FlightInventory $inventory = null): FlightInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = $this->generateFlightInventory();
        $tourComponent = new FlightInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

    function generateTransportInventoryTour(?Tour $tour = null, string $componentType = 'Included', float $cost = 100, ?TransportInventory $inventory = null): TransportInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = $this->generateTransportInventory();
        $tourComponent = new TransportInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

    function generateAccommodationUpgrade(AccommodationInventoryTour|null $component = null, float $cost = 100): AccommodationInventoryTour
    {
        $component = $component ?? $this->generateAccommodationInventoryTour();
        $upgrade = $this->generateAccommodationInventoryTour($component->tour, 'Upgrade', $cost);
        AccommodationInventoryTourUpgrade::create([
            'base_id' => $component->id,
            'upgrade_id' => $upgrade->id,
        ]);
        return $upgrade;
    }

    function generateActivityUpgrade(ActivityInventoryTour|null $component = null, float $cost = 100): ActivityInventoryTour
    {
        $component = $component ?? $this->generateActivityInventoryTour();
        $upgrade = $this->generateActivityInventoryTour($component->tour, 'Upgrade', $cost);
        ActivityInventoryTourUpgrade::create([
            'base_id' => $component->id,
            'upgrade_id' => $upgrade->id,
        ]);
        return $upgrade;
    }
    function generateFlightUpgrade(FlightInventoryTour|null $component = null, float $cost = 100): FlightInventoryTour
    {
        $component = $component ?? $this->generateFlightInventoryTour();
        $upgrade = $this->generateFlightInventoryTour($component->tour, 'Upgrade', $cost);
        FlightInventoryTourUpgrade::create([
            'base_id' => $component->id,
            'upgrade_id' => $upgrade->id,
        ]);
        return $upgrade;
    }
    function generateTransportUpgrade(TransportInventoryTour|null $component = null, float $cost = 100): TransportInventoryTour
    {
        $component = $component ?? $this->generateTransportInventoryTour();
        $upgrade = $this->generateTransportInventoryTour($component->tour, 'Upgrade', $cost);
        TransportInventoryTourUpgrade::create([
            'base_id' => $component->id,
            'upgrade_id' => $upgrade->id,
        ]);
        return $upgrade;
    }

}
