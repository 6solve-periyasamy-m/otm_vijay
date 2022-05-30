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
use Throwable;

/**
 * Static Repository for Rooming and Occupancy
 * TODO: Rework with Occupancy Rework
 */
class RoomingRepository
{
    /**
     *
     * @param Order $order
     * @throws RoomingFailedException
     * @throws Throwable
     * @var Group $group
     */
    public static function buildGroupRooming(Order $order, $data): void
    {
        try {
            DB::beginTransaction();
            $inflated = self::inflateRoomingData($data);
            foreach ($order->groups() as $group) {
                $group->delete();
            }
            foreach ($inflated as $groupData) {
                $group = Group::create([
                    'room_type_id' => $groupData->room_type->id,
                    'name' => $groupData->name,
                ]);
                foreach ($groupData->customers as $customer) {
                    $group->orderCustomers()->save($customer);
                }
                self::addRoomsToGroup($order, $group);
            }
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            throw new RoomingFailedException($e);
        }
    }

    /**
     * @throws RoomingFailedException
     */
    private static function inflateRoomingData($data): array
    {
        $inflated = [];
        foreach ($data as $object) {
            Log::error($data);
            $collection = new Collection();
            $roomType = RoomType::find($object['roomType']);
            if (!isset($roomType)) throw new RoomingFailedException('An invalid room type was provided');
            $collection->room_type = $roomType;
            $members = [];
            $collection->name = $object['name'];
            if (!array_key_exists('customers', $object)) continue;
            foreach ($object['customers'] as $customerId) {
                $customer = OrderCustomer::find($customerId);
                if (!isset($customer)) throw new RoomingFailedException('An invalid customer was provided');
                $members[] = $customer;
            }
            if (sizeof($members) < 1) continue;
            $collection->customers = $members;
            $inflated[] = $collection;
        }
        return $inflated;
    }

    /**
     * @throws RoomingFailedException
     */
    public static function addRoomsToGroup(Order $order, Group $group): void
    {
        $templates = RoomingRepository::getTemplateTourInventory($order->tour);
        $roomType = $group->roomType;
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
     * @return Collection
     */
    public static function getTemplateTourInventory(Tour $tour): Collection
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

    public static function checkOccupancy(OrderCustomer $orderCustomer): bool
    {
        $owned = [];
        foreach ($orderCustomer->orderAccommodation() as $orderAccommodation) {
            $date = $orderAccommodation->tourComponent->inventory->check_in->clone()->setTime(0, 0);
            $owned[$date->unix()] = $orderAccommodation;
        }
        foreach ($orderCustomer->order->tour->templates as $template) {
            $date = $template->inventory->check_in->clone()->setTime(0, 0, 0);
            if (array_key_exists($date->unix(), $owned)) continue;
            return false;
        }
        return true;
    }

    public static function assignDefaultRooming(OrderCustomer $orderCustomer): bool
    {
        $singleRoom = null;
        foreach (RoomingRepository::getAvailableRoomTypes($orderCustomer->order->tour) as $roomType) {
            if ($singleRoom != null && $singleRoom->maximum_occupancy <= $roomType->maximum_occupancy) continue;
            $singleRoom = $roomType;
            if ($singleRoom->maximum_occupancy == 1) break;
        }
        if (!isset($singleRoom)) return false;
        $group = Group::create([
            'room_type_id' => $singleRoom->id,
            'name' => $orderCustomer->customer_name,
        ]);
        (new GroupRepository($group))->addCustomerToGroup($orderCustomer);
        try {
            self::addRoomsToGroup($orderCustomer->order, $group);
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

    public static function exportRoomingData(Order $order): array
    {
        $groups = [];
        $usedIds = [];
        foreach ($order->groups() as $group) {
            $grouping = ['name' => $group->name, 'roomType' => ['id' => $group->room_type_id, 'name' => $group->roomType->name, 'size' => $group->roomType->maximum_occupancy],];
            $customers = [];
            foreach ($group->orderCustomers as $orderCustomer) {
                $customers[] = ['id' => $orderCustomer->id, 'name' => $orderCustomer->customer_name, 'avatar' => asset($orderCustomer->customer->profile_picture),];
                $usedIds[] = $orderCustomer->id;
            }
            $grouping['customers'] = $customers;
            $groups[] = $grouping;
        }
        $data['rooms'] = [];
        foreach (RoomingRepository::getAvailableRoomTypes($order->tour) as $roomType) {
            $data['rooms'][] = ['id' => $roomType->id, 'name' => $roomType->name, 'size' => $roomType->maximum_occupancy,];
        }
        $data['groups'] = $groups;
        $data['customers'] = [];
        $data['unused'] = [];
        $unused = array_diff(self::getOrderCustomerIds($order), $usedIds);
        foreach ($order->orderCustomers as $orderCustomer) {
            $customerData = ['id' => $orderCustomer->id, 'name' => $orderCustomer->customer_name, 'avatar' => asset($orderCustomer->customer->profile_picture),];
            $data['customers'][] = $customerData;
            if (in_array($orderCustomer->id, $unused)) {
                $data['unused'][] = $customerData;
            }
        }
        return $data;
    }

    private static function getOrderCustomerIds(Order $order): array
    {
        $query = DB::table('order_customers')->where('order_id', '=', $order->id)->select('id');
        $ids = [];
        foreach ($query->get() as $result) {
            $ids[] = $result->id;
        }
        return $ids;
    }

    public static function getSizeList(Tour $tour): array
    {
        $availableSizes = RoomingRepository::getAvailableRoomSizes($tour);
        $availableTypes = [];
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            $roomTypes = RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($inventoryTour));
            foreach ($roomTypes as $type) {
                if (in_array($type->maximum_occupancy, $availableSizes)) $availableTypes[] = $type->id;
            }
        }
        return RoomingRepository::hydrateRoomTypes(array_unique($availableTypes));
    }

    public static function getHydratedRoomTypesForInventory(AccommodationInventoryTour $inventoryTour): array
    {
        return RoomingRepository::hydrateRoomTypes(RoomingRepository::getRoomTypesForInventory($inventoryTour));
    }
}