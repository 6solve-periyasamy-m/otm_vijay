<?php

namespace App\Repository;

use App\Models\OrdersCustomer;
use App\Models\OrdersTransport;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface TransportComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getAvailableAddons(Tour $tour, OrdersCustomer $orderCustomer = null);

    public static function getBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);
}

class TransportComponentRepository implements TransportComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrdersTransport::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory()->first()->transport();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersTransport::findOrFail($orderComponentId);
        return $orderComponent->transportInventoryTour()->first()->transportInventory();
    }

    public static function getAvailableAddons(Tour $tour, OrdersCustomer $orderCustomer = null)
    {
        $components = [];
        foreach ($tour->transportInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->transportInventory->transport->name;
                $components[$component->id]['transport_type'] = $component->transportInventory->transport->transportType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderTransports as $oComponent) {
                $component = $oComponent->transportInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $transportInventoryTourId)
    {
        return OrdersTransport::create([
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
        $query->join('locations AS departure_locations', 'transports.departure_location_id', '=', 'departure_locations.id');
        $query->join('locations AS arrival_locations', 'transports.arrival_location_id', '=', 'arrival_locations.id');
        $query->select(
            'transport_inventories.id AS id',
            'transports.id AS transport_id',
            'transports.name AS name',
            'transport_types.name AS transport_type',
            'operators.name AS operator_name',
            'transports.description AS description',
            DB::raw('CASE WHEN `transports`.`is_domestic` = 1 THEN \'Yes\' ELSE \'No\' END AS is_domestic'),
            'departure_locations.name AS departure_location',
            'transport_inventories.departure_date_time AS departure_date',
            DB::raw('CASE WHEN `transport_inventories`.`departure_confirmed` = 1 THEN \'Yes\' ELSE \'No\' END AS departure_confirmed'),
            'arrival_locations.name AS arrival_location',
            'transport_inventories.arrival_date_time AS arrival_date',
            DB::raw('CASE WHEN `transport_inventories`.`arrival_confirmed` = 1 THEN \'Yes\' ELSE \'No\' END AS arrival_confirmed'),
            DB::raw('CASE WHEN `transport_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END AS fit_selectable'),
            'transport_inventories.stock AS stock',
            'transport_inventories.purchase_price AS purchase_price',
            'transport_inventories.sales_price AS sales_price',
            'transport_inventories.notes AS notes'
        );
        $query->whereNotIn('transport_inventories.id', $alreadyAdded);
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `transport_inventories`.`departure_date_time` AND `transport_inventories`.`arrival_date_time`");
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `transport_inventories`.`departure_date_time` AND `transport_inventories`.`arrival_date_time`");
        return $query->get();
    }
}
