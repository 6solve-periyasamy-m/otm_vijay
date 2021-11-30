<?php

namespace App\Repository;

use App\Models\OrderCustomer;
use App\Models\OrderTransport;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface TransportComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);
}

class TransportComponentRepository implements TransportComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrderTransport::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory()->first()->transport();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->transportInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->transportInventory->transport->name;
                $components[$component->id]['transport_type'] = $component->transportInventory->transport->transportType->name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderTransports as $oComponent) {
                $component = $oComponent->transportInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $transportInventoryTourId)
    {
        return OrderTransport::create([
            'order_customer_id' => $oCustomerId,
            'transport_inventory_tour_id' => $transportInventoryTourId,
        ]);
    }

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $alreadyAdded = [];
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            $alreadyAdded[$inventoryTour->transportInventory->id] = $inventoryTour->transportInventory->id;
        }
        $query = DB::table('transport_inventories');
        $query->join('transports', 'transport_inventories.transport_id', '=', 'transports.id');
        $query->join('operators', 'transports.operator_id', '=', 'operators.id');
        $query->join('transport_types', 'transports.transport_type_id', '=', 'transport_types.id');
        $query->join('addresses AS departure_locations', 'transports.departure_address_id', '=', 'departure_locations.id');
        $query->join('addresses AS arrival_locations', 'transports.arrival_address_id', '=', 'arrival_locations.id');
        $query->join('travel_classes', 'transport_inventories.travel_class_id', '=', 'travel_classes.id');
        $query->select(
            'transport_inventories.id AS id',
            'transports.id AS transport_id',
            'transports.name AS name',
            'transport_types.name AS transport_type',
            'operators.name AS operator_name',
            'transports.description AS description',
            'travel_classes.name AS travel_class',
            DB::raw('CASE WHEN `transports`.`is_domestic` = 1 THEN \'Yes\' ELSE \'No\' END AS is_domestic'),
            'departure_locations.name AS departure_location',
            'transport_inventories.departs_at AS departure_date',
            DB::raw('CASE WHEN `transport_inventories`.`departure_time_confirmed` = 1 THEN \'Yes\' ELSE \'No\' END AS departure_time_confirmed'),
            'arrival_locations.name AS arrival_location',
            'transport_inventories.arrives_at AS arrival_date',
            DB::raw('CASE WHEN `transport_inventories`.`arrival_time_confirmed` = 1 THEN \'Yes\' ELSE \'No\' END AS arrival_time_confirmed'),
            DB::raw('CASE WHEN `transport_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END AS fit_selectable'),
            'transport_inventories.stock AS stock',
            'transport_inventories.purchase_price AS purchase_price',
            'transport_inventories.sales_price AS sales_price',
            'transport_inventories.notes AS notes'
        );
        $query->whereNotIn('transport_inventories.id', $alreadyAdded);
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `transport_inventories`.`departs_at` AND `transport_inventories`.`arrives_at`");
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `transport_inventories`.`departs_at` AND `transport_inventories`.`arrives_at`");
        return $query->get();
    }
}
