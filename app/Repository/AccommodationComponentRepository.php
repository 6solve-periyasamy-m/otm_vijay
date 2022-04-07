<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\Accommodation\OrderCustomerAccommodationAddedEvent;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Accommodation\RoomType;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

interface AccommodationComponentRepositoryInterface
{
    public static function getAvailableAddons($tourId, $oCustomerId = -1);

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null);

    public static function getParentComponent(AccommodationInventoryTour $inventoryTour);
}

class AccommodationComponentRepository implements AccommodationComponentRepositoryInterface
{

    public static function getAvailableAddons($tourId, $oCustomerId = -1): array
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->tour_component_type  !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
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

    public static function grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId): OrderAccommodation|null
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
        event(new OrderCustomerAccommodationAddedEvent($orderComponent));
        return $orderComponent;
    }

    public static function getParentComponent(AccommodationInventoryTour $inventoryTour): AccommodationInventoryTour
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        if (!isset($upgrade)) return $inventoryTour;
        return $upgrade->base;
    }

    public static function getInventoryWithRoomType(AccommodationInventoryTour $inventoryTour, RoomType $roomType): ?AccommodationInventoryTour
    {
        $inventory = $inventoryTour->inventory;

        $query = DB::table('accommodation_inventory_tours');
        $query->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', '=', 'accommodation_inventories.id');
        $query->whereRaw("DATE(`accommodation_inventories`.`check_in`) = '{$inventory->check_in->format('Y-m-d')}'");
        $query->where('accommodation_inventories.room_type_id', '=', $roomType->id);
        $query->where('accommodation_inventory_tours.tour_id', '=', $inventoryTour->tour_id);
        $query->where('accommodation_inventory_tours.tour_component_type', '=', 'Included');
        $query->whereNull('accommodation_inventory_tours.deleted_at');
        $query->select('accommodation_inventory_tours.id');

        return $query->first() == null ? null : AccommodationInventoryTour::find($query->first()->id);
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
        $sizes = self::getAvailableRoomSizes($tour);
        $available = [];
        foreach ($templates as $template) {
            $availableTypes = self::hydrateRoomTypes(self::getRoomTypesForInventory($template));
            foreach ($availableTypes as $type) {
                if (in_array($type->maximum_occupancy, $sizes)) {
                    $available[] = $type->id;
                }
            }
        }
        return self::hydrateRoomTypes(array_unique($available));
    }

    public static function getAvailableRoomSizes(Tour $tour): array
    {
        $templates = self::getTemplateTourInventory($tour);
        $first = true;
        $available = [];
        foreach ($templates as $template) {
            if ($first && empty($available)) {
                $types = self::hydrateRoomTypes(self::getRoomTypesForInventory($template));
                foreach ($types as $type) {
                    $available[] = $type->maximum_occupancy;
                }
                $available = array_unique($available);
                continue;
            }
            $roomTypes = self::hydrateRoomTypes(self::getRoomTypesForInventory($template));
            $types = [];
            foreach ($roomTypes as $type) {
                $types[] = $type->maximum_occupancy;
            }
            $types = array_unique($types);
            $missing = array_diff($available, $types);
            $available = array_diff($available, $missing);
        }
        return $available;
    }

    public static function getSizeList(Tour $tour): array
    {
        $availableSizes = self::getAvailableRoomSizes($tour);
        $availableTypes = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $roomTypes = self::hydrateRoomTypes(self::getRoomTypesForInventory($inventoryTour));
            foreach ($roomTypes as $type) {
                if (in_array($type->maximum_occupancy, $availableSizes)) $availableTypes[] = $type->id;
            }
        }
        return self::hydrateRoomTypes(array_unique($availableTypes));
    }

    public static function getRoomTypesForInventory(AccommodationInventoryTour $inventoryTour): array
    {
        $inventory = $inventoryTour->inventory;
        $accommodation = $inventory->component;

        $query = DB::table('accommodation_inventory_tours');
        $query->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', '=', 'accommodation_inventories.id');
        $query->whereRaw("DATE(`accommodation_inventories`.`check_in`) = '{$inventory->check_in->format('Y-m-d')}'");
        $query->where('accommodation_inventory_tours.tour_id', '=', $inventoryTour->tour_id);
        $query->where('accommodation_inventory_tours.tour_component_type', '=', 'Included');
        $query->whereNull('accommodation_inventory_tours.deleted_at');
        $query->select('accommodation_inventories.room_type_id AS id');

        $available = [];
        foreach ($query->get('id') as $result) {
            $available[] = $result->id;
        }

        return $available;
    }

    public static function hydrateRoomTypes(array $ids): array
    {
        $types = [];
        foreach ($ids as $id) {
            $types[] = RoomType::find($id);
        }
        return $types;
    }

    public static function getHydratedRoomTypesForInventory(AccommodationInventoryTour $inventoryTour): array
    {
        return self::hydrateRoomTypes(self::getRoomTypesForInventory($inventoryTour));
    }

    public static function isOnUpgradeTree(AccommodationInventoryTour $inventoryTour, AccommodationInventoryTourUpgrade $upgrade): bool
    {
        if ($upgrade->base_id == $inventoryTour->id) return true;
        foreach ($inventoryTour->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public static function getAvailableBetweenDates(Tour $tour, Carbon $dateFrom = null, Carbon $dateTo = null): Collection
    {
        $inventories = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $inventories[] = $inventoryTour->inventory->id;
        }
        return AccommodationInventory::whereBetween('check_in', [$dateFrom, $dateTo])->whereBetween('check_out', [$dateFrom, $dateTo])->whereNotIn('id', $inventories)->get();
    }
}
