<?php

namespace Tests\Traits\Model;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;

trait TestsTour
{
    use TestsAccommodation;
    use TestsActivity;
    use TestsFlight;
    use TestsTransport;
    use TestsMerchandise;

    function generateTour(): Tour
    {
        return Tour::factory()->create();
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

}
