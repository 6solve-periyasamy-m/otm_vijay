<?php

namespace App\Repository;

use Exception;
use App\Models\Tour;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Accommodation;
use App\Models\OrdersCustomer;
use App\Models\CustomerOrderDetail;
use Illuminate\Support\Facades\Log;
use App\Models\BookingAccommodation;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;

interface AccommodationRepositoryInterface {
    public function __construct();
    public function getAccommodationInventoryData($tour);
    public function getAccommodationInventoryForTour(Tour $tour);
    public function getAccommodationBooking(Booking $booking, $travellerIds);
    public function loadRoomsForTour(Tour $tour);
    public function updateAccommodationBooking($booking, $room, $traveller, $customer_id);

    // TODO: Deprecate!
    // public function updateAccommodationReservation(
    //     $order_id,
    //     $room,
    //     $traveller,
    //     $customer_id,
    //     $reference);
    public function remove($tourIds, $groupIds);
}

class AccommodationRepository implements AccommodationRepositoryInterface
{
    protected $model;
    private $debug = null;

    private function logger($level, $message, ...$params) {
        if ($this->debug > $level) {
            Log::info($message, $params);
        }
    }

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
        $inventory = new AccommodationInventory();
        $result = $inventory->select(
            'accommodations.name as accommodation_name',
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id',
            'accommodation_inventories.*',
            'board_types.name as board_type',
            'room_types.name as room_type',
            'room_types.maximum_occupancy')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('accommodation_inventory_tours', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();

        return $result;
    }

    /***
     * populate the customerOrderDetails
     * accommodation records for an inventory ID
     */
    public function getAccommodationBooking(Booking $booking, $travellerIds)
    {
        $bookingAccommodation = new BookingAccommodation();
        $bookings = $bookingAccommodation
            ->select('booking_accommodations.booking_id', 'booking_accommodations.customer_id', 'booking_accommodations.accommodation_inventory_id',
                'accommodation_inventory_tours.booking_policy', 'room_types.maximum_occupancy',
                'accommodations.name as accommodation_name',
                'board_types.name as board_type_name', 'room_types.name as room_type_name')
            ->join('accommodation_inventories', 'booking_accommodations.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('accommodation_inventory_tours', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->where('accommodation_inventory_tours.tour_id', $booking->tour_id)
            ->whereIn('booking_accommodations.customer_id', $travellerIds)
            ->distinct()
            ->get();

        return $bookings;
    }

    public function loadRoomsForTour(Tour $tour)
    {
        /*
            select ait.tour_id,`room_type_name`, maximum_occupancy, board_type_name, stock, ai.sales_price, ait.sales_price as tour_sales_price, ai.booking_policy
            from accommodation_inventory_tours ait
            join accommodation_inventories ai on ait.accommodation_inventory_id=ai.id
            join room_types rt on rt.id=ai.room_type_id
            join board_types bt on bt.id=ai.board_type_id
            where tour_id=2
        */
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
        
        $this->debug > 5 && Log::info( 'rooms for tour', $rooms->toArray());
        
        return $rooms;
    }

    private function update($booking) {
        $bookingAccommodation = new BookingAccommodation();
        $current = $bookingAccommodation->where('customer_id', $booking['customer_id'])
            ->where('booking_id', $booking['booking_id'])->get();
        if ($current->count() > 1) {
            var_dump($current);
            Log::error('BookingAccommodation table has a duplicate record for customer booking', $booking);
            throw new Exception('more than one accommodation booking record found');
        }
        if ($current->count() === 1) {
            $updateBooking = $current[0];
            $updateBooking->accommodation_inventory_id = $booking['accommodation_inventory_id'];
            $updateBooking->save();
            return true;
        }
        $bookingAccommodation->customer_id = $booking['customer_id'];
        $bookingAccommodation->accommodation_inventory_id = $booking['accommodation_inventory_id'];
        $bookingAccommodation->booking_id = $booking['booking_id'];
        $bookingAccommodation->save();
        return true;
    }

    public function updateAccommodationBooking($booking, $room, $traveller, $customer_id)
    {
        //Log::debug('UpdateAccommodationBooking', [$booking, $room, $traveller, $customer_id]);

        /**
         * customer_id books room
         * if shares, then shares is an array of customer_id
         */
        Log::debug('UpdateAccommodationBooking', [$room]);
        $booking_id = $booking->id;
        $customer_id = $customer_id;
        $room_share_ids = null;
        $accommodation_inventory_id = null;
        if (isset($room) && count($room)) {
            $accommodation_inventory_id = $room['accommodation_inventory_id'];
            if (isset($traveller) && isset($traveller['shared'])) {
                $room_share_ids = $traveller['shares'];
            }
        }
        Log::debug('data:', [$booking_id, $customer_id, $accommodation_inventory_id, $room_share_ids]);
        // create the booking records 
        if (empty($accommodation_inventory_id)) {
            return false;
        }
        $booking = [
            'booking_id' => $booking_id,
            'customer_id' => $customer_id,
            'accommodation_inventory_id' => $accommodation_inventory_id,
        ];
Log::debug('updating booking', $booking);
        try {
            $this->update($booking);
            if ($room_share_ids) {
                foreach($room_share_ids as $share_id) {
                    $booking = [
                        'booking_id' => $booking_id,
                        'customer_id' => $share_id,
                        'accommodation_inventory_id' => $accommodation_inventory_id,
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
