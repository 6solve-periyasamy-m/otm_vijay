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

Log::debug('AIT', ['query' => $query->toSql()]);

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
            ->select('booking_accommodations.*',
                'room_types.maximum_occupancy',
                'room_types.name as room_type_name',
                //'board_types.name as board_type_name'
            ) 
            ->join('room_types', 'booking_accommodations.room_type_id', 'room_types.id')
            //->join('board_types', 'booking_accommodations.board_type_id', 'board_types.id')
            ->where('booking_accommodations.id', $booking->id)
            ->whereIn('booking_accommodations.customer_id', $travellerIds);
        try {
            $bookings = $bookingObj->get();
            $this->debug && Log::debug('getAccommodationBooking', [$booking, $travellerIds, $bookings]);
        } catch (Exception $e) {
            Log::error('Retrieving booking data error: ' . $e->getMessage());
            throw new Exception('error with bookingAccommodation query', $e->getMessage());
        }
        return $bookings;
    }

    private function update($booking) 
    {

        $bookingAccommodation = new BookingAccommodation();
        $current = $bookingAccommodation
            ->where('customer_id', $booking['customer_id'])
            ->where('booking_id', $booking['booking_id'])
            ->get();

        if ($current->count() > 1) {
            Log::error('BookingAccommodation table has a duplicate record for customer booking', $booking);
            throw new Exception('more than one accommodation booking record found');
        }
        if ($current->count() === 1) {
            $updateBooking = $current[0];
            $updateBooking->room_type = $booking->room_type;
            $updateBooking->group = $booking->group;
            $updateBooking->save();
            return true;
        }

        return true;
    }

    private function updateGroup($bookingAccommodation, $group) 
    {
      $bookingAccommodation->group = $group;
      try {
        $bookingAccommodation->save();
        return true;
      } catch (Exception $e) {
        Log::error('DB Error updating BookingAccommodation record', $e->getMessage());
        return false;
      }
    }

    private function create($booking, $customer_id, $room_type, $group) {
      $bookingAccommodation = new BookingAccommodation();
      $bookingAccommodation->booking_id = $booking->id;
      $bookingAccommodation->room_type_id = $room_type;
      $bookingAccommodation->group = $group;

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
        list($token, $customer_id, $room_type, $group) = $data;
        
        $booking = BookingRepository::findBooking($token);
        if (empty($booking)) {
          Log::warning('makeAccommodationBooking with unknown token in data array?', $data);
          return false;
        }
        $bookingAccommodation = BookingAccommodation::where('booking_id', $booking->id)
            ->where('customer_id', $customer_id)
            ->where('room_type_id', $room_type)
            ->first();

        if ($bookingAccommodation) {
            $this->updateGroup($bookingAccommodation, $group);
        } else {
            $this->create($booking, $customer_id, $room_type, $group);
        }
    }

    public function updateAccommodationBooking(Booking $booking, $customer_id, $room_type, $group)
    {
      $bookingAccommodation = BookingAccommodation::where('booking_id', $booking->id)->where('customer_id', $customer_id)->first();
      Log::debug('BookingAccommodationUpdate:', [$bookingAccommodation]);
      $bookingAccommodation->room_type_id = $room_type;
      $bookingAccommodation->group = $group;
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
          $bookingAccommodation = BookingAccommodation::where('booking_id', $booking->id)->where('customer_id', $customer_id)->first();
          if ($bookingAccommodation) {
             $bookingAccommodation->delete();
          }
      } catch (Exception $e) {
          Log::error('Error updating AccommodationBooking '.$booking->id, $e->getMessage());
          return false;
      }

      return true;
    }
}

