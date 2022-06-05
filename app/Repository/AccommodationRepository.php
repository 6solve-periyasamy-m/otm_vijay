<?php

namespace App\Repository;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\Component\BookingAccommodation;
use App\Models\Tour\Tour;
use Exception;
use Illuminate\Support\Facades\Log;

interface AccommodationRepositoryInterface {
    public function __construct();
    public static function loadRoomsForTour(Tour $tour);
    public function getAccommodationInventoryData($tour);
    public function getAccommodationInventoryForTour(Tour $tour);
    public function getAccommodationBooking(Booking $booking, $travellerIds);
    public function makeAccommodationBooking(Array $data);
    public function updateAccommodationBooking(Booking $booking, $customer_id, $room_type, $group);
    public function remove(Booking $booking);
}

class AccommodationRepository implements AccommodationRepositoryInterface
{
    protected $model;
    private $debug = 0;

    public function __construct()
    {
        $this->model = new Accommodation();
    }

    /**
     * loadRoomsForTour
     *
     * @param Tour $tour
     * @return Array $rooms
     */
    public static function loadRoomsForTour(Tour $tour)
    {
        $rooms = RoomingRepository::getAvailableRoomTypes($tour);

        return $rooms;
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
        $query = $inventoryTour->select(
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
            ->where('accommodation_inventory_tours.tour_id', $tour->id);
        $result = $query->get();

        $this->debug && Log::debug('getAccommodationInventoryForTour', [$result]);
        return $result;
    }

    /***
     * accommodation records for an inventory ID
     */
    public function getAccommodationBooking(Booking $booking, $travellerIds)
    {
        $bookingAccommodation = new BookingAccommodation();
        $bookingObj = $bookingAccommodation
            ->select('booking_accommodations.group_id',
                'booking_accommodations.customer_id',
                'booking_accommodations.room_type_id',
                'accommodation_groups.name as group_name',
                'room_types.maximum_occupancy',
                'room_types.name as room_type_name'
            ) 
            ->join('room_types', 'booking_accommodations.room_type_id', 'room_types.id')
            ->join('bookings', 'booking_accommodations.booking_id', 'bookings.id')
            ->join('customers', 'bookings.customer_id', 'customers.id')
            ->leftJoin('accommodation_groups', 'booking_accommodations.group_id', 'accommodation_groups.id')
            ->where('booking_accommodations.booking_id', $booking->id)
            ->whereIn('booking_accommodations.customer_id', $travellerIds);
        try {
            $bookings = $bookingObj->get();
            $this->debug === 5 && Log::debug('>>> getAccommodationBooking', [$bookingObj->toSql(), $bookings]);
        } catch (Exception $e) {
            Log::error('Retrieving booking data error: ' . $e->getMessage());
            throw new Exception('error with bookingAccommodation query'. $e->getMessage());
        }
        //Log::debug('GetAccommodationBooking result:', [$bookings, $bookingObj->toSql()]);

        return $bookings;
    }

    // NOT BEING USED: probably not needed
    // private function update($booking) 
    // {

    //     $bookingAccommodation = new BookingAccommodation();
    //     $current = $bookingAccommodation
    //         ->where('customer_id', $booking['customer_id'])
    //         ->where('booking_id', $booking['booking_id'])
    //         ->get();

    //     if ($current->count() > 1) {
    //         Log::error('BookingAccommodation table has a duplicate record for customer booking', $booking);
    //         throw new Exception('more than one accommodation booking record found');
    //     }
    //     if ($current->count() === 1) {
    //         $updateBooking = $current[0];
    //         $updateBooking->room_type = $booking->room_type;
    //         $updateBooking->group_id = $booking->group_id;
    //         $updateBooking->save();
    //         return true;
    //     }

    //     return true;
    // }

    private function updateGroup($bookingAccommodation, $room_type, $group_id) 
    {
      $bookingAccommodation->room_type_id = $room_type;
      $bookingAccommodation->group_id = $group_id;
      try {
        $bookingAccommodation->save();
        return true;
      } catch (Exception $e) {
        Log::error('DB Error updating BookingAccommodation '. $e->getMessage());
        return false;
      }
    }

    private function create($booking, $customer_id, $room_type, $group_id) {
      $bookingAccommodation = new BookingAccommodation();
      $bookingAccommodation->booking_id = $booking->id;
      $bookingAccommodation->customer_id = $customer_id;
      $bookingAccommodation->room_type_id = $room_type;
      $bookingAccommodation->group_id = isset($group_id) ? $group_id : 0;

      try {
        $bookingAccommodation->save();
        return true;
      } catch (Exception $e) {
        Log::error('DB Error creating BookingAccommodation '. $e->getMessage());
        return false;
      }
    }

    public function makeAccommodationBooking(Array $data) 
    {
        list($token, $customer_id, $room_type, $group_id) = $data;
        
        $booking = BookingRepository::findBooking($token);
        if (empty($booking)) {
          Log::warning('makeAccommodationBooking with unknown token in data array?', $data);
          return false;
        }
        $bookingAccommodation = BookingAccommodation::where('booking_id', $booking->id)
            ->where('customer_id', $customer_id)
            ->first();
        if ($bookingAccommodation) {
            $this->updateGroup($bookingAccommodation, $room_type, $group_id);
        } else {
            $this->create($booking, $customer_id, $room_type, $group_id);
        }
    }

    public function updateAccommodationBooking(Booking $booking, $customer_id, $room_type, $group_id)
    {
      $bookingAccommodation = BookingAccommodation::where('booking_id', $booking->id)->where('customer_id', $customer_id)->first();
      $bookingAccommodation->room_type_id = $room_type;
      $bookingAccommodation->group_id = isset($group_id) ? $group_id : 0;
      try {
          $booking->save();
      } catch (Exception $e) {
          Log::error('Error updating AccommodationBooking '.$booking->id, $e->getMessage());
      }

      return $booking;
    }

    public function remove(Booking $booking)
    {
      try {
          $bookingAccommodation = BookingAccommodation::where('booking_id', $booking->id)->delete();
      } catch (Exception $e) {
          Log::error('Error updating AccommodationBooking '.$booking->id. $e->getMessage());
          return false;
      }

      return true;
    }
}

