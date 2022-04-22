<?php

namespace App\Repository;

use App\Models\AccommodationGroup;
use App\Models\ActivityInventoryTour;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\Booking;
use App\Models\BookingAccommodation;
use App\Models\BookingActivities;
use App\Models\BookingFlight;
use App\Models\BookingTransport;
use App\Models\BookingTraveller;
use App\Models\Customer;
use App\Models\FlightInventoryTour;
use App\Models\RoomType;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use Log;
use StringFormatter;
use Throwable;

class CustomerBookingRepository
{
    public static function generateBooking(Tour $tour, Customer $customer, RoomType $roomType, AccommodationGroup $group): Booking
    {
        do {
            $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(64/strlen($x)) )),1,64);
            $booking = Booking::where('token', $token)->first();
        } while (isset($booking));
        $name = $customer->first_name . ' ' . $customer->last_name . ' [' . $tour->name . ']';
        $booking = Booking::create(['customer_id' => $customer->id, 'tour_id' => $tour->id, 'token' => $token, 'name' => $name,]);
        $traveller = $booking->travellers()->save(BookingTraveller::make(['customer_id' => $customer->id,]));
        self::addIncludedToBookingTraveller($traveller, $roomType, $group);
        return $booking;
    }

    public static function addCustomerToBooking(Booking $booking, Customer $customer, RoomType $roomType, AccommodationGroup $group): BookingTraveller
    {
        $traveller = $booking->travellers()->save(BookingTraveller::make(['customer_id' => $customer->id,]));
        /** @var BookingTraveller $traveller */
        self::addIncludedToBookingTraveller($traveller, $roomType, $group);
        return $traveller;
    }

    public static function selectFlights(Booking $booking, FlightInventoryTour $outbound, FlightInventoryTour $inbound): bool
    {
        /** @var Tour $tour */
        $tour = $booking->tour;
        if ($outbound->tour_id !== $tour->id || $inbound->tour_id !== $tour->id) return false;
        $booking->flights()->delete();
        foreach ($booking->travellers as $traveller) {
            $bookingComponent = BookingFlight::make([
                'customer_id' => $traveller->customer_id,
                'flight_inventory_tour_id' => $outbound->id,
                'flight_type' => $outbound->flight_type,
            ]);
            $booking->flights()->save($bookingComponent);

            $bookingComponent = BookingFlight::make([
                'customer_id' => $traveller->customer_id,
                'flight_inventory_tour_id' => $inbound->id,
                'flight_type' => $inbound->flight_type,
            ]);
            $booking->flights()->save($bookingComponent);
        }
        return true;
    }

    public static function addIncludedToBookingTraveller(BookingTraveller $traveller, RoomType $roomType, AccommodationGroup $group)
    {
        /** @var Booking $booking */
        $booking = $traveller->booking;
        /** @var Tour $tour */
        $tour = $booking->tour;
        // Accommodation
        foreach ($tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Included') {
                if ($inventoryTour->inventory->room_type_id !== $roomType->id) continue;
                $bookingComponent = BookingAccommodation::make([
                    'customer_id' => $traveller->customer_id,
                    'accommodation_inventory_tour_id' => $inventoryTour->id,
                    'room_type_id' => $roomType->id,
                    'group_id' => $group->id,
                ]);
                $booking->accommodation()->save($bookingComponent);
            }
        }
        // Activity
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Included') {
                $bookingComponent = BookingActivities::make([
                    'customer_id' => $traveller->customer_id,
                    'activity_inventory_tour_id' => $inventoryTour->id,
                ]);
                $booking->activities()->save($bookingComponent);
            }
        }
        // Flights are Selected Later
        // Transport
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Included') {
                $bookingComponent = BookingTransport::make([
                    'customer_id' => $traveller->customer_id,
                    'transport_inventory_tour_id' => $inventoryTour->id,
                ]);
                $booking->transports()->save($bookingComponent);
            }
        }
    }

    /**
     * @throws Throwable
     */
    public static function upgradeBookingActivity(Booking $booking, ActivityInventoryTour $from, ActivityInventoryTourUpgrade $to): bool
    {
        if (($booking->tour_id !== $from->tour_id) || ($booking->tour_id !== $to->upgrade->tour_id)) return false;
        if (!ActivityComponentRepository::isOnUpgradeTree($from, $to)) return false;
        try {
            DB::beginTransaction();
            DB::table('booking_activities')
                ->where('booking_id', '=', $booking->id)
                ->where('activity_inventory_tour_id', '=', $from->id)
                ->update(['activity_inventory_tour_id' => $to->upgrade->id,]);
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
        if ($addon->tour_component_type !== 'Add-on') return false;
        foreach ($booking->travellers as $traveller) {
            $booking->activities()->save(BookingActivities::make(['customer_id' => $traveller->customer->id,'activity_inventory_tour_id' => $addon->id,]));
        }
        return true;
    }

    public static function generateSummary(Booking $booking): array
    {
        $tour = $booking->tour;
        $summary = ['customers' => [], 'billing' => ['additionals' => 0, 'single_occupants' => 0, 'surcharge' => $tour->single_occupancy_surcharge, 'cost' => $tour->base_price_per_person, 'deposit' => $tour->deposit],];
        $customerCount = 0;
        foreach ($booking->travellers as $traveller) {
            $customerCount++;
            $customer = $traveller->customer;
            $summary['customers'][$customer->id] = ['customer' => $customer, 'components' => ['accommodation' => [], 'activities' => [], 'flights' => [], 'transport' => [],]];
            $summary['billing']['single_occupants'] = $summary['billing']['single_occupants'] + $traveller->is_single_occupant;
            // Accommodation
            foreach ($traveller->accommodation as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['accommodation'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out),
                    'description' => $inventory->component->name . ' (' . $inventory->roomType->name . ') (' . $inventory->boardType . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Activity
            foreach ($traveller->activities as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['activities'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->starts_at) . ' to ' . StringFormatter::formatDateTime($inventory->ends_at),
                    'description' => $inventory->component->name . ' (' . $inventory->component->address . ')' . ' (' . $inventory->ticketType . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Flights
            foreach ($traveller->flights as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['flights'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at),
                    'description' => $inventory->component->departureAirport . ' to ' . $inventory->component->arrivalAirport . ' (' . $inventory->travelClass . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Transport
            foreach ($traveller->transport as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['transport'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->departs_at) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at),
                    'description' => $inventory->component->name . ' (' . $inventory->component->transportType . ') (' . $inventory->travelClass . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
        }
        $summary['billing']['customers'] = $customerCount;
        $summary['billing']['total'] = ($tour->base_price_per_person * $customerCount) + ($summary['billing']['single_occupants'] * $summary['billing']['surcharge']) + $summary['billing']['additionals'];
        $summary['billing']['today'] = $tour->deposit * $customerCount;

        return $summary;
    }
}
