<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;
use App\Models\AccommodationInventoryTourUpgrade;
use App\Models\OrderAccommodation;
use App\Models\OrderCustomer;
use App\Models\RoomType;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

interface AccommodationComponentRepositoryInterface
{
    public static function getComponentFromOrderComponent($orderComponentId);

    public static function getInventoryFromOrderComponent($orderComponentId);

    public static function getOrderComponentFromId($orderComponentId);

    public static function getAvailableAddons($tourId, $oCustomerId = -1);

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);

    public static function getParentComponent(AccommodationInventoryTour $inventoryTour);
}

class AccommodationComponentRepository implements AccommodationComponentRepositoryInterface
{
    public static function getOrderComponentFromId($orderComponentId)
    {
        return OrderAccommodation::findOrFail($orderComponentId);
    }

    public static function getComponentFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public static function getInventoryFromOrderComponent($orderComponentId)
    {
        $orderComponent = OrderAccommodation::findOrFail($orderComponentId);
        return $orderComponent->accommodationInventoryTour()->first()->accommodationInventory();
    }

    public static function getAvailableAddons($tourId, $oCustomerId = -1)
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->tour_component_type == "Add-on") {
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->accommodationInventory->accommodation->name;
                $components[$component->id]['room_type'] = $component->accommodationInventory->roomType->name;
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
        $orderCustomer = OrderCustomer::find($oCustomerId);
        $group = $orderCustomer->primary_group;
        if (!isset($group)) return null;
        $orderComponent = OrderAccommodation::create([
            'group_id' => $group->id,
            'accommodation_inventory_tour_id' => $accommodationInventoryTourId,
            'share_with_user_id' => null,
            'cost' => AccommodationInventoryTour::findOrFail($accommodationInventoryTourId)->tour_sales_price,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent;
    }

    public static function getAvailableBetweenDatesOld(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $alreadyAdded = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $alreadyAdded[$inventoryTour->accommodationInventory->id] = $inventoryTour->accommodationInventory->id;
        }
        $query = DB::table('accommodation_inventories');
        $query->join('accommodations', 'accommodation_inventories.accommodation_id', '=', 'accommodations.id');
        $query->join('addresses', 'accommodations.address_id', '=', 'addresses.id');
        $query->join('countries', 'countries.id', '=', 'addresses.country_id');
        $query->join('room_types', 'accommodation_inventories.room_type_id', '=', 'room_types.id');
        $query->join('board_types', 'accommodation_inventories.board_type_id', '=', 'board_types.id');
        $query->select(
            'accommodation_inventories.id AS id',
            'accommodations.id AS accommodation_id',
            'accommodations.name AS accommodation_name',
            DB::raw('CONCAT(`addresses`.`region`, \' - \', `countries`.`name`) AS location'),
            'accommodation_inventories.check_in AS check_in',
            'accommodation_inventories.check_out AS check_out_time',
            DB::raw('CASE WHEN `accommodation_inventories`.`check_in_time_confirmed` = 1 THEN \'Yes\' ELSE \'No\' END  AS check_in_time_confirmed'),
            DB::raw('CASE WHEN `accommodation_inventories`.`check_out_time_confirmed` = 1 THEN \'Yes\' ELSE \'No\' END  AS check_out_confirmed'),
            'room_types.name AS room_type',
            'board_types.name AS board_type',
            DB::raw('CASE WHEN `accommodation_inventories`.`fit_selectable` = 1 THEN \'Yes\' ELSE \'No\' END  AS fit_selectable'),
            'accommodation_inventories.stock AS stock',
            'accommodation_inventories.purchase_price AS purchase_price',
            'accommodation_inventories.sales_price AS sales_price',
            'accommodation_inventories.notes as notes'
        );
        $query->whereNotIn('accommodation_inventories.id', $alreadyAdded);
        if (isset($dateFrom)) $query = $query->whereRaw("'" . $dateFrom->format('Y-m-d') . "' BETWEEN `accommodation_inventories`.`check_in` AND `accommodation_inventories`.`check_out`");
        if (isset($dateTo)) $query = $query->whereRaw("'" . $dateTo->format('Y-m-d') . "' BETWEEN `accommodation_inventories`.`check_in` AND `accommodation_inventories`.`check_out`");
        return  $query->get();
    }

    public static function getParentComponent(AccommodationInventoryTour $inventoryTour) {
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        if (!isset($upgrade)) return $inventoryTour;
        return $upgrade->base;
    }

    public static function getInventoryWithRoomType(AccommodationInventoryTour $inventoryTour, RoomType $roomType): AccommodationInventoryTour
    {
        $inventory = $inventoryTour->inventory;
        $accommodation = $inventory->component;

        $query = DB::table('accommodation_inventory_tours');
        $query->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', '=', 'accommodation_inventories.id');
        $query->where('accommodation_inventories.accommodation_id', '=', $accommodation->id);
        $query->where('accommodation_inventories.check_in', '=', $inventory->check_in);
        $query->where('accommodation_inventories.check_out', '=', $inventory->check_out);
        $query->where('accommodation_inventories.room_type_id', '=', $roomType->id);
        $query->where('accommodation_inventory_tours.tour_id', '=', $inventoryTour->tour_id);
        $query->where('accommodation_inventory_tours.tour_component_type', '=', 'Included');
        $query->select('accommodation_inventory_tours.id');

        return AccommodationInventoryTour::find($query->first()->id);
    }

    /**
     * @param Tour $tour
     * @return Collection
     */
    public static function getTemplateTourInventory(Tour $tour): Collection
    {
        return AccommodationInventoryTour::where('tour_id', '=', $tour->id)->where('is_template', '=', 1)->get();
    }

    public static function getAvailableRoomTypes(Tour $tour): array
    {
        $templates = self::getTemplateTourInventory($tour);
        $first = true;
        $available = [];
        foreach ($templates as $template) {
            if ($first && empty($available)) {
                $available = self::getRoomTypesForInventory($template);
                continue;
            }
            $roomTypes = self::getRoomTypesForInventory($template);
            $missing = array_diff($available, $roomTypes);
            $available = array_diff($available, $missing);
        }
        return self::hydrateRoomTypes($available);
    }

    private static function getRoomTypesForInventory(AccommodationInventoryTour $inventoryTour): array
    {
        $inventory = $inventoryTour->inventory;
        $accommodation = $inventory->component;

        $query = DB::table('accommodation_inventory_tours');
        $query->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', '=', 'accommodation_inventories.id');
        $query->where('accommodation_inventories.accommodation_id', '=', $accommodation->id);
        $query->where('accommodation_inventories.check_in', '=', $inventory->check_in);
        $query->where('accommodation_inventories.check_out', '=', $inventory->check_out);
        $query->where('accommodation_inventory_tours.tour_id', '=', $inventoryTour->tour_id);
        $query->where('accommodation_inventory_tours.tour_component_type', '=', 'Included');
        $query->select('accommodation_inventories.room_type_id AS id');

        $available = [];
        foreach ($query->get('id') as $result) {
            $available[] = $result->id;
        }

        return $available;
    }

    private static function hydrateRoomTypes(array $ids): array
    {
        $types = [];
        foreach ($ids as $id) {
            $types[] = RoomType::find($id);
        }
        return $types;
    }

    public static function isOnUpgradeTree(AccommodationInventoryTour $inventoryTour, AccommodationInventoryTourUpgrade $upgrade): bool
    {
        if ($upgrade->base_id == $inventoryTour->id) return true;
        foreach ($inventoryTour->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        $inventories = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return AccommodationInventory::whereBetween('check_in', [$dateFrom, $dateTo])->whereBetween('check_out', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }
}
