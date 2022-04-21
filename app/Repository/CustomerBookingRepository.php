<?php

namespace App\Repository;

use App\Models\AccommodationGroup;
use App\Models\Booking;
use App\Models\BookingAccommodation;
use App\Models\BookingActivities;
use App\Models\BookingFlight;
use App\Models\BookingTransport;
use App\Models\BookingTraveller;
use App\Models\Customer;
use App\Models\RoomType;
use App\Models\Tour;

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
        // Flight
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type == 'Included') {
                $bookingComponent = BookingFlight::make([
                    'customer_id' => $traveller->customer_id,
                    'flight_inventory_tour_id' => $inventoryTour->id,
                    'flight_type' => $inventoryTour->flight_type,
                ]);
                $booking->flights()->save($bookingComponent);
            }
        }
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
}
