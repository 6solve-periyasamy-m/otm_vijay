<?php

namespace App\Repository;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Group;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Merchandise;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;

class CustomerBookingRepository
{
    /**
     * @throws Throwable
     */
    public static function upgradeBookingActivity(Booking $booking, ActivityInventoryTour $from, ActivityInventoryTour $to): bool
    {
        if (($booking->tour_id !== $from->tour_id) || ($booking->tour_id !== $to->tour_id)) return false;
        if (!$to->is_bookable) return false;
        if ($to->available_stock < $booking->travellers()->count()) return false;
        try {
            DB::beginTransaction();
            foreach ($booking->travellers as $traveller) {
                DB::table('booking_activities')
                    ->where('booking_traveller_id', '=', $traveller->id)
                    ->where('activity_inventory_tour_id', '=', $from->id)
                    ->update(['activity_inventory_tour_id' => $to->id,]);
            }
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
        return true;
    }

    public static function addBookingActivityAddon(Booking $booking, ActivityInventoryTour $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if (!$addon->is_bookable) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        if ($addon->available_stock < $booking->travellers()->count()) return false;
        foreach ($booking->travellers as $traveller) {
            $traveller->repository->addComponent($addon->repository);
        }
        return true;
    }

    public static function addBookingMerchandiseAddon(Booking $booking, Merchandise $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if (!$addon->is_bookable) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        if ($addon->available_stock < $booking->travellers()->count()) return false;
        foreach ($booking->travellers as $traveller) {
            $traveller->repository->addComponent($addon->repository);
        }
        return true;
    }

    public static function removeBookingActivityAddon(Booking $booking, ActivityInventoryTour $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        foreach ($booking->travellers as $traveller) {
            $traveller->activities()->where('activity_inventory_tour_id', $addon->id)->delete();
        }
        return true;
    }

    public static function removeBookingMerchandiseAddon(Booking $booking, Merchandise $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        foreach ($booking->travellers as $traveller) {
            $traveller->merchandise()->where('merchandise_id', $addon->id)->delete();
        }
        return true;
    }

    public static function getBookingAdditionals(BookingTraveller $bookingTraveller): array
    {
        if (!isset($bookingTraveller)) return [];
        $owned = self::getBookedComponentsAndUpgradedIncluded($bookingTraveller);
        $data = [];
        foreach ($bookingTraveller->booking->tour->merchandise as $tourComponent) {
            $owns = in_array($tourComponent->id, $owned['extras']);
            if ($tourComponent->available_stock <= 0 && !$owned) continue;
            $data[] = ['id' => $tourComponent->id, 'name' => $tourComponent->name, 'component' => 'extra', 'type' => $tourComponent->tour_component_type,
                'cost' => $tourComponent->tour_sales_price, 'date' => now()->unix(), 'owned' => $owns,];
        }
        foreach ($bookingTraveller->booking->tour->activityInventoryTours as $tourComponent) {
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['activities'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'activity', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->starts_at->unix(),'owned' => false,];
            }
        }
        return $data;
    }

    /**
     * Mostly the same in implementation to Order equivalent. Generates more data than is needed, as it is not worth
     * stripping it out when it needs adding again later
     * @param BookingTraveller $traveller
     * @return array
     */
    public static function getBookedComponentsAndUpgradedIncluded(BookingTraveller $traveller): array
    {
        $data = [];
        $subData = [];
        foreach ($traveller->accommodation as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['accommodation'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->activities as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['activities'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->flights as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['flights'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->transport as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['transport'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->merchandise as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
        }
        $data['extras'] = array_unique($subData);
        return $data;
    }

    public static function getLeadTraveller(?Booking $booking): ?BookingTraveller
    {
        if (!isset($booking)) return null;
        return BookingTraveller::where('booking_id', $booking->id)->where('customer_id', $booking->customer_id)->first();
    }

    public static function getAdditionalTravellers(?Booking $booking): array
    {
        if (!isset($booking)) return [];
        $customers = [];
        foreach ($booking->travellers as $traveller) {
            $customers[$traveller->customer_id] = $traveller;
        }
        $customers[$booking->customer_id] = null;
        return $customers;

    }

    public static function convertBookingToOrder(Booking $booking): Order
    {
        $tour = $booking->tour;
        $order = Order::create([
            'tour_id' => $booking->tour_id,
            'ordered_on' => now(),
            'deposit' => $tour->deposit,
            'invoice_footer' => $tour->invoice_footer
        ]);
        $leadBooker = OrderCustomer::make([
            'customer_id' => $booking->customer_id,
            'tour_cost' => $tour->base_price_per_person,
            'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
        ]);

        StaticOrderRepository::cloneInstallments($order);

        $customers = [];
        $order->orderCustomers()->save($leadBooker);

        $customers[$booking->customer_id] = $leadBooker;
        $order->lead_booker_id = $leadBooker->id;
        $order->booking_reference = Order::generateBookingReference($order);
        $order->save();
        //event(new OrderCreatedEvent($order));
        $leadTraveller = self::getLeadTraveller($booking);
        self::buildComponents($order, $leadTraveller, $leadBooker);
        $orderCustomers = [$leadBooker->customer_id => $leadBooker,];
        foreach ($booking->travellers as $traveller) {
            if ($traveller->id == $leadTraveller->id) continue;
            $orderCustomer = self::buildComponents($order, $traveller);
            $orderCustomers[$orderCustomer->customer_id] = $orderCustomer;
        }
        $groups = [];
        foreach ($booking->accommodation as $accommodation) {
            $group = key_exists($accommodation->group_id, $groups) ? $groups[$accommodation->group_id]
                : Group::create(['name' => $accommodation->group->name, 'room_type_id' => $accommodation->room_type_id,]);
            $groups[$accommodation->group_id] = $group;
            $oCustomer = $orderCustomers[$accommodation->customer_id];
            $groupRepo = new GroupRepository($group);
            $groupRepo->addCustomerToGroup($oCustomer);
            $groupRepo->addRoomToGroup($accommodation->tourComponent);
        }
        return $order;
    }

    private static function buildComponents(Order $order, BookingTraveller $traveller, ?OrderCustomer $orderCustomer = null): OrderCustomer
    {
        if (!isset($orderCustomer)) {
            $orderCustomer = OrderCustomer::make([
                'customer_id' => $traveller->customer_id,
                'tour_cost' => $order->tour->base_price_per_person,
                'single_occupancy_surcharge' => $order->tour->single_occupancy_surcharge,
            ]);
            $order->orderCustomers()->save($orderCustomer);
        }
        foreach ($traveller->activities as $bookingComponent) {
            $orderCustomer->orderActivities()->save(OrderActivity::make([
                'activity_inventory_tour_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        foreach ($traveller->flights as $bookingComponent) {
            $orderCustomer->orderFlights()->save(OrderFlight::make([
                'flight_inventory_tour_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        foreach ($traveller->transport as $bookingComponent) {
            $orderCustomer->orderTransports()->save(OrderTransport::make([
                'transport_inventory_tour_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        foreach ($traveller->merchandise as $bookingComponent) {
            $orderCustomer->orderMerchandise()->save(OrderMerchandise::make([
                'merchandise_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        return $orderCustomer;
    }
}
