<?php

namespace App\Repository;

use App\Models\OrdersAccommodation;
use App\Models\OrdersCustomer;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface AccommodationComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getAvailableAddons($tourId, $oCustomerId = -1);

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);
}

class AccommodationComponentRepository implements AccommodationComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrdersAccommodation::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrdersAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId = -1)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrdersCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->accommodationInventory->accommodation->title;
                $components[$component->id]['room_type'] = $component->accommodationInventory->roomType->room_type_name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderAccommodation as $oComponent) {
                $component = $oComponent->accommodationInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId)
    {
        return OrdersAccommodation::create([
            'order_customer_id' => $oCustomerId,
            'accommodation_inventory_tour_id' => $accommodationInventoryTourId,
            'share_with_user_id' => null
        ]);
    }

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $alreadyAdded = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $alreadyAdded[$inventoryTour->accommodationInventory->id] = $inventoryTour->accommodationInventory->id;
        }
        $query = DB::table('accommodation_inventories');
        $query->join('accommodations', 'accommodation_inventories.accommodation_id', '=', 'accommodations.id');
        $query->join('regions', 'accommodations.region_id', '=', 'regions.id');
        $query->join('countries', 'regions.country_id', '=', 'countries.id');
        $query->join('room_types', 'accommodation_inventories.room_type_id', '=', 'room_types.id');
        $query->join('board_types', 'accommodation_inventories.board_type_id', '=', 'board_types.id');
        $query->select(
            'accommodation_inventories.id AS id',
            'accommodations.id AS accommodation_id',
            'accommodations.title AS accommodation_name',
            DB::raw('CONCAT(`regions`.`name`, \' - \', `countries`.`name`) AS location'),
            'accommodation_inventories.check_in_date_time AS check_in_time',
            'accommodation_inventories.check_out_date_time AS check_out_time',
            DB::raw('CASE WHEN `accommodation_inventories`.`checked_in` = 1 THEN \'Yes\' ELSE \'No\' END  AS checked_in'),
            DB::raw('CASE WHEN `accommodation_inventories`.`checked_out` = 1 THEN \'Yes\' ELSE \'No\' END  AS check_out_confirmed'),
            'room_types.room_type_name AS room_type',
            'board_types.board_type_name AS board_type',
            DB::raw('CASE WHEN `accommodation_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END  AS fit_selectable'),
            'accommodation_inventories.stock AS stock',
            'accommodation_inventories.purchase_price AS purchase_price',
            'accommodation_inventories.sales_price AS sales_price',
            'accommodation_inventories.notes as notes'
        );
        $query->whereNotIn('accommodation_inventories.id', $alreadyAdded);
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `accommodation_inventories`.`check_in_date_time` AND `accommodation_inventories`.`check_out_date_time`");
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `accommodation_inventories`.`check_in_date_time` AND `accommodation_inventories`.`check_out_date_time`");
        return  $query->get();
    }
}
