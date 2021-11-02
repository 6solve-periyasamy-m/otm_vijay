<?php

namespace App\Repository;

use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface FlightComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);
}

class FlightComponentRepository implements FlightComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrderFlight::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderFlight::findOrFail($orderComponentId);
        return $orderComponent->flightInventoryTour()->first()->flightInventory()->first()->flight();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderFlight::findOrFail($orderComponentId);
        return $orderComponent->flightInventoryTour()->first()->flightInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->flightInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->flightInventory->flight_number;
                $components[$component->id]['travel_class'] = $component->flightInventory->travelClass->name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderFlights as $oComponent) {
                $component = $oComponent->flightInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $flightInventoryTourId)
    {
        return OrderFlight::create([
            'order_customer_id' => $oCustomerId,
            'flight_inventory_tour_id' => $flightInventoryTourId,
        ]);
    }

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $alreadyAdded = [];
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            $alreadyAdded[$inventoryTour->flightInventory->id] = $inventoryTour->flightInventory->id;
        }
        $query = DB::table('flight_inventories');
        $query->join('flights', 'flight_inventories.flight_id', '=', 'flights.id');
        $query->join('travel_classes', 'flight_inventories.travel_class_id', '=', 'travel_classes.id');
        $query->join('airports AS arrival_airports', 'flights.arrival_airport_id', '=', 'arrival_airports.id');
        $query->join('airports AS departure_airports', 'flights.departure_airport_id', '=', 'departure_airports.id');
        $query->join('airlines', 'flights.airline_id', '=', 'airlines.id');
        $query->select(
            'flight_inventories.id AS id',
            'flights.id AS flight_id',
            'flight_inventories.flight_number AS flight_number',
            'travel_classes.name AS travel_class',
            DB::raw('CASE WHEN `flights`.`is_domestic` = 1 THEN \'Yes\' ELSE \'No\' END AS is_domestic'),
            'flights.available_after AS available_after',
            'flight_inventories.check_in AS check_in',
            'flight_inventories.departs_at AS departure_time',
            'departure_airports.name AS departure_airport',
            'flight_inventories.arrives_at AS arrival_time',
            'arrival_airports.name AS arrival_airport',
            DB::raw('CASE WHEN `flight_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END AS fit_selectable'),
            'flight_inventories.stock AS stock',
            'flight_inventories.purchase_price AS purchase_price',
            'flight_inventories.sales_price AS sales_price',
            'flight_inventories.notes AS notes'
        );
        $query->whereNotIn('flight_inventories.id', $alreadyAdded);
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `flight_inventories`.`departs_at` AND `flight_inventories`.`arrives_at`");
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `flight_inventories`.`departs_at` AND `flight_inventories`.`arrives_at`");
        return $query->get();
    }
}
