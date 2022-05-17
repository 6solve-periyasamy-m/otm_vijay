<?php

namespace Tests\Traits;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Tour\Merchandise;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;

trait TestsTour
{
    use TestsAccommodation;

    function generateTour(): Tour
    {
        return Tour::factory()->create();
    }

    function generateMerchandise(?Tour $tour, float $cost, string $tour_component_type): Merchandise
    {
        if (!isset($tour)) $tour = $this->generateTour();
        $merchandise = Merchandise::factory()->make(['tour_component_type' => $tour_component_type, 'tour_sales_price' => $cost,]);
        $tour->merchandise()->save($merchandise);
        return $merchandise;
    }

    function generateAccommodationInventoryTour(?Tour $tour, string $componentType, float $cost, ?AccommodationInventory $inventory = null): AccommodationInventoryTour
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

    function generateActivityInventoryTour(?Tour $tour, string $componentType, float $cost, ?ActivityInventory $inventory = null): ActivityInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = ActivityInventory::all()->first();
        $tourComponent = new ActivityInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

    function generateFlightInventoryTour(?Tour $tour, string $componentType, float $cost, ?FlightInventory $inventory = null): FlightInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = FlightInventory::all()->first();
        $tourComponent = new FlightInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

    function generateTransportInventoryTour(?Tour $tour, string $componentType, float $cost, ?TransportInventory $inventory = null): TransportInventoryTour
    {
        if (!isset($tour)) $tour = $this->generateTour();
        if (!isset($inventory)) $inventory = TransportInventory::all()->first();
        $tourComponent = new TransportInventoryTour([
            'tour_component_type' => $componentType,
            'tour_sales_price' => $cost,
            'tour_id' => $tour->id,
        ]);
        $inventory->tourComponents()->save($tourComponent);
        return $tourComponent;
    }

}
