<?php

namespace App\Repository;

use App\Models\Accommodation;
use App\Models\AccommodationInventory;
use App\Models\OrdersAccommodation;
use App\Models\OrdersCustomer;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

interface AccommodationComponentRepositoryInterface {
    public static function getComponentFromOrderComponent($orderComponentId);
    public static function getInventoryFromOrderComponent($orderComponentId);
    public static function getOrderComponentFromId($orderComponentId);
    public static function getAvailableAddons($tourId, $oCustomerId = -1);
    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);
    public static function getBetweenDates(Carbon $dateFrom = null, Carbon $dateTo = null);
}

class AccommodationComponentRepository implements AccommodationComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId) {
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

    public static function getAvailableAddons($tourId, $oCustomerId = -1) {
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

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId) {
        return OrdersAccommodation::create([
            'order_customer_id' => $oCustomerId,
            'accommodation_inventory_tour_id' => $accommodationInventoryTourId,
            'share_with_user_id' => null
        ]);
    }

    public static function getBetweenDates(Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $query = DB::table('accommodation_inventories');
        $query->join('accommodations', 'accommodation_inventories.accommodation_id', '=', 'accommodations.id');
        $query->join('regions', 'accommodations.region_id', '=', 'regions.id');
        $query->join('countries', 'regions.country_id', '=', 'countries.id');
        $query->select(
            'accommodation_inventories.id AS id',
            'accommodations.id AS accommodation_id',
            'accommodations.title AS accommodation_name',
            'regions.name AS region_name',
            'countries.name AS country_name',
            'accommodation_inventories.check_in_date_time AS check_in_time',
            'accommodation_inventories.check_out_date_time AS check_out_time',
            'accommodation_inventories.checkin_confirmed AS check_in_confirmed',
            'accommodation_inventories.checkout_confirmed AS check_out_confirmed',
            DB::raw('CASE WHEN `accommodation_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END  AS fit_selectable'),
            'accommodation_inventories.stock AS stock',
            'accommodation_inventories.purchase_price AS purchase_price',
            'accommodation_inventories.sales_price AS sales_price',
            'accommodation_inventories.notes as notes'
        );
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `accommodation_inventories`.`check_in_date_time` AND `accommodation_inventories`.`check_out_date_time`" );
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `accommodation_inventories`.`check_in_date_time` AND `accommodation_inventories`.`check_out_date_time`" );
        return $query->get();
    }
}
