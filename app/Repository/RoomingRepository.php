<?php

namespace App\Repository;

use App\Exceptions\RoomingFailedException;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\RoomType;
use App\Models\Customer\Group;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * Static Repository for Rooming and Occupancy
 */
class RoomingRepository
{

    /**
     * @throws RoomingFailedException
     */
    public static function addRoomsToGroup(Order $order, Group $group, RoomType $roomType): void
    {
        $templates = RoomingRepository::getTemplateTourInventory($order->tour);
        foreach ($templates as $template) {
            $found = RoomingRepository::getInventoryWithRoomType($template, $roomType);
            if (!isset($found)) {
                $types = RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($template));
                foreach ($types as $type) {
                    if ($type->maximum_occupancy == $roomType->maximum_occupancy) {
                        $found = RoomingRepository::getInventoryWithRoomType($template, $type);
                        break;
                    }
                }
            }
            if (!isset($found)) throw new RoomingFailedException("Template {$template->id} has no inventory of type {$roomType->name} or size {$roomType->maximum_occupancy}");
            OrderAccommodation::create([
                'accommodation_inventory_tour_id' => $found->id,
                'group_id' => $group->id,
                'cost' => $found->tour_sales_price,
            ]);
        }
    }

    /**
     * @param Tour $tour
     * @return Collection|AccommodationInventoryTour[]
     */
    public static function getTemplateTourInventory(Tour $tour): array|Collection
    {
        return AccommodationInventoryTour::where('tour_id', '=', $tour->id)->where('is_template', '=', 1)->get();
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
     * @param array $ids
     * @return RoomType[]
     */
    public static function hydrateRoomTypes(array $ids): array
    {
        $types = [];
        foreach ($ids as $id) {
            $types[] = RoomType::find($id);
        }
        return $types;
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

    public static function getSingleRoomType(Tour $tour): RoomType|null
    {
        $singleRoom = null;
        foreach (RoomingRepository::getAvailableRoomTypes($tour) as $roomType) {
            if ($singleRoom != null && $singleRoom->maximum_occupancy <= $roomType->maximum_occupancy) continue;
            $singleRoom = $roomType;
            if ($singleRoom->maximum_occupancy == 1) break;
        }
        return $singleRoom;
    }

    public static function assignDefaultRooming(OrderCustomer $orderCustomer): bool
    {
        $singleRoom = static::getSingleRoomType($orderCustomer->order->tour);
        if (empty($singleRoom)) return false;
        $group = Group::create();
        $group->repository->addCustomerToGroup($orderCustomer);
        try {
            self::addRoomsToGroup($orderCustomer->order, $group, $singleRoom);
            return true;
        } catch (RoomingFailedException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function getAvailableRoomTypes(Tour $tour): array
    {
        $templates = RoomingRepository::getTemplateTourInventory($tour);
        $sizes = RoomingRepository::getAvailableRoomSizes($tour);
        $available = [];
        foreach ($templates as $template) {
            $availableTypes = RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($template));
            foreach ($availableTypes as $type) {
                if (in_array($type->maximum_occupancy, $sizes)) {
                    $available[] = $type->id;
                }
            }
        }
        return RoomingRepository::hydrateRoomTypes(array_unique($available));
    }

    public static function getAvailableRoomSizes(Tour $tour): array
    {
        $templates = RoomingRepository::getTemplateTourInventory($tour);
        $first = true;
        $available = [];
        foreach ($templates as $template) {
            if ($first && empty($available)) {
                $types = RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($template));
                foreach ($types as $type) {
                    $available[] = $type->maximum_occupancy;
                }
                $available = array_unique($available);
                continue;
            }
            $roomTypes = RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($template));
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

    /**
     * @param AccommodationInventoryTour $inventoryTour
     * @return RoomType[]
     */
    public static function getHydratedRoomTypesForInventory(AccommodationInventoryTour $inventoryTour): array
    {
        return RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($inventoryTour));
    }

    /**
     * Returns a list of rooms to be given by default to a traveller
     * @return AccommodationInventoryTour[]
     */
    public static function getDefaultRoomList(Tour $tour): array
    {
        $singleRoom = null;
        $availableTypes = [];
        foreach (RoomingRepository::getAvailableRoomTypes($tour) as $roomType) {
            if ($singleRoom != null && $singleRoom->maximum_occupancy < $roomType->maximum_occupancy) continue;
            if (!isset($singleRoom) || $roomType?->maximum_occupancy < $singleRoom->maximum_occupancy) {
                $singleRoom = $roomType;
                $availableTypes = [];
            }
            $availableTypes[] = $roomType;
        }
        if (!isset($singleRoom)) return [];
        $rooms = [];
        foreach ($tour->templates as $template) {
            $room = self::getInventoryWithRoomType($template, $singleRoom);
            if (empty($room)) {
                for ($x = 1; $x < sizeof($availableTypes); $x++) {
                    $room = self::getInventoryWithRoomType($template, $availableTypes[$x]);
                    if (!empty($room)) break;
                }
            }
            if (!empty($room)) {
                $rooms[] = $room;
            }
        }
        return $rooms;
    }

    /**
     * @param OrderCustomer $orderCustomer
     * @param AccommodationInventoryTour[] $rooms
     * @return void
     */
    public static function createGroupFromRoomList(OrderCustomer $orderCustomer, array $rooms): void
    {
        if (!empty($rooms)) {
            $group = Group::create();
            $group->repository->addCustomerToGroup($orderCustomer);
            $count = 0;
            foreach ($rooms as $room) {
                $count++;
                if (empty($room)) {
                    continue;
                }
                $group->repository->addRoomToGroup($room);
            }
        }
    }
}
