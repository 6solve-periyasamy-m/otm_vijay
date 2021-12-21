<?php

namespace App\Repository;

use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\Accommodation;
use App\Models\CustomerOrderDetail;
use App\Models\BookingAccommodation;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;

interface AccommodationRepositoryInterface {
    public function __construct();
    public function getAccommodationInventoryData($tour);
    public function getAccommodationInventoryForTour(Tour $tour);
    public function getAccommodationBooking(Booking $booking, $travellerIds);
    public function loadRoomsForTour(Tour $tour);
    public function updateAccommodationBooking($booking, $room, $traveller, $customer_id, $accommodation_inventory_tour_id);
    public function remove($tourIds, $groupIds);
}

class AccommodationRepository implements AccommodationRepositoryInterface
{
    protected $model;
    private $debug = 0;

    public function __construct()
    {
        $this->model = new Accommodation();
    }

    public function getAccommodationInventoryData($tour)
    {
        $inventory = new AccommodationInventory();
        $result = $inventory->map(function ($accommodationInventory) {
            return [
                "id" => $accommodationInventory->id,
                "accommodation_id" => $accommodationInventory->accommodation->id,
                "check_in_date_time" => $accommodationInventory->check_in_date_time->format('Y-m-d H:i:s'),
                "check_out_date_time" => $accommodationInventory->check_out_date_time->format('Y-m-d H:i:s'),
                "accommodation_name" => $accommodationInventory->accommodation->title,
                "accommodation_address" => $accommodationInventory->accommodation->address,
                "room_type" => $accommodationInventory->roomType->room_type_name,
                "board_type" => $accommodationInventory->boardType->board_type_name,
                "booking_policy" => $accommodationInventory->booking_policy,
            ];
        })->toArray();

        return $result;
    }

    public function getAccommodationInventoryForTour(Tour $tour)
    {
        $inventoryTour = new AccommodationInventoryTour();
        $result = $inventoryTour->select(
            'accommodations.name as accommodation_name',
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id',
            'accommodation_inventories.check_in',
            'accommodation_inventories.check_out',
            'board_types.name as board_type',
            'room_types.name as room_type',
            'room_types.maximum_occupancy')
            ->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();

        Log::debug('getAccommodationInventoryForTour', [$result]);
        return $result;
    }

    /***
     * accommodation records for an inventory ID
     */
    public function getAccommodationBooking(Booking $booking, $travellerIds)
    {
        $bookingAccommodation = new BookingAccommodation();
        $booking = $bookingAccommodation
            ->select('booking_accommodations.booking_id',
                'booking_accommodations.customer_id', 
                'booking_accommodations.accommodation_inventory_tour_id',
                'accommodation_inventory_tours.booking_policy', 'room_types.maximum_occupancy',
                'accommodations.name as accommodation_name',
                'board_types.name as board_type_name', 'room_types.name as room_type_name')
            ->join('accommodation_inventory_tours', 'booking_accommodations.accommodation_inventory_tour_id', 'accommodation_inventory_tours.id')
            ->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->where('accommodation_inventory_tours.tour_id', $booking->tour_id)
            ->whereIn('booking_accommodations.customer_id', $travellerIds);
        
        $bookings = $booking->get();
        Log::debug('getAccommodationBooking', [$booking->toSql(), $travellerIds, $bookings]);

        return $bookings;
    }

    public function loadRoomsForTour(Tour $tour)
    {
        $tours = new AccommodationInventoryTour();
        $rooms = $tours->select(
            'accommodations.name as accommodation_name',
            'accommodation_inventories.accommodation_id',
            'accommodation_inventories.id as accommodation_inventory_id',
            'accommodation_inventory_tours.tour_id',
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id',
            'room_types.id as room_type_id',
            'room_types.name as room_type_name',
            'room_types.maximum_occupancy',
            'board_types.id as board_type_id',
            'board_types.name as board_type_name'
        );
        $rooms = $rooms->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();

        $this->debug > 5 && Log::debug( 'rooms for tour', $rooms->toArray());
        
        return $rooms;
    }

    private function update($booking) 
    {
        Log::debug('Accommodation update:', [$booking]);

        $bookingAccommodation = new BookingAccommodation();
        $current = $bookingAccommodation
            ->where('customer_id', $booking['customer_id'])
            ->where('booking_id', $booking['booking_id'])
            ->get();

        if ($current->count() > 1) {
            Log::error('BookingAccommodation table has a duplicate record for customer booking', $booking);
            var_dump($current);
            throw new Exception('more than one accommodation booking record found');
        }
Log::debug('accommodation update loaded', [$current]);
        if ($current->count() === 1) {
            $updateBooking = $current[0];
            $updateBooking->accommodation_inventory_tour_id = $booking['accommodation_inventory_tour_id'];
            $updateBooking->save();
            return true;
        }

        $bookingAccommodation->customer_id = $booking['customer_id'];
        $bookingAccommodation->accommodation_inventory_tour_id = $booking['accommodation_inventory_tour_id'];
        $bookingAccommodation->booking_id = $booking['booking_id'];
        $bookingAccommodation->save();
        return true;
    }

    public function updateAccommodationBooking($booking, $room, $traveller, $customer_id, $accommodation_inventory_tour_id)
    {
        /**
         * customer_id books room
         * if shares, then shares is an array of customer_id
         */
// inspect room: does accommodation_inventory_id exist here?
// inspect $accommodation_inventory_tour_id passed in
Log::debug('UpdateAccommodationBooking (id, room, booking) ', [$accommodation_inventory_tour_id, $room, $booking]);
        $booking_id = $booking->id;
        $customer_id = $customer_id;
        $room_share_ids = null;
        $accommodation_inventory_id = null;
        if (isset($room) && count($room)) {
            $accommodation_inventory_tour_id = $room['accommodation_inventory_tour_id'];
            if (isset($traveller) && isset($traveller['shared'])) {
                $room_share_ids = $traveller['shares'];
            }
        }
        $this->debug && Log::debug('data:', [$booking_id, $customer_id, $accommodation_inventory_tour_id, $room_share_ids]);
        // create the booking records 
        if (empty($accommodation_inventory_tour_id)) {
            return false;
        }
        $booking = [
            'booking_id' => $booking_id,
            'customer_id' => $customer_id,
            'accommodation_inventory_tour_id' => $accommodation_inventory_tour_id,
        ];
        
        $this->debug && Log::debug('updating booking', $booking);
        try {
            $this->update($booking);
            if ($room_share_ids) {
                foreach($room_share_ids as $share_id) {
                    $booking = [
                        'booking_id' => $booking_id,
                        'customer_id' => $share_id,
                        'accommodation_inventory_tour_id' => $accommodation_inventory_tour_id,
                    ];
                    $this->update($booking);
                }
            }
        } catch(Exception $e) {
            Log::error('exception encountered updating accommodation booking: '.$e->getMessage());
            throw new Exception('error updating booking: ' . $e->getMessage());
        }
        return false;
    }

    public function remove($tourIds, $groupIds) 
    {
        CustomerOrderDetail::whereIn('inventory_tour_id', $tourIds)
            ->whereIn('orders_customer_id', $groupIds)
            ->delete();
    }
}
