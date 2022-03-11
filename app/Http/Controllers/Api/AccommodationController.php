<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\RoomingFailedException;
use App\Models\Order;
use App\Repository\OrderRepository;
use App\Repository\TourRepository;
use Exception;
use App\Models\Tour;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiController;
use App\Models\RoomType;
use App\Models\AccommodationGroup;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;

use App\Repository\AccommodationRepository;
use App\Repository\BookingTravellerRepository;
use App\Repository\BookingRepository;

class AccommodationController extends ApiController
{
    protected $component_type = 'accommodation';
    private $debug = 0;

    /***
     * this tour has a range of accommodation options: 
     * @Param: Tour $tour
     * returns: data: room_types
     */
    public function getAccommodationOptions(Tour $tour)
    {
        $room_types = RoomType::select('id', 'name', 'maximum_occupancy') 
          ->whereNull('deleted_at')
          ->get();

        return response()->json(['success' => true, 'options' => [ 'room_types' => $room_types ]]);
    }

    public function getAccommodationGroups()
    {
        $groups = AccommodationGroup::get();

        return response()->json(['success' => true, 'groups' => $groups]);
    }

    /**
     * getAccommodationForTour
     * 
     * returns all accommodation inventory records that have been associated with this tour
     * 
     * @param Tour $tour
     * @return JSON
     */
    public function getAccommodationInventoryForTour(Tour $tour)
    {
        $accommodationRepository = new AccommodationRepository();
        $result = $accommodationRepository->getAccommodationInventoryForTour($tour);

        return response()->json(["success" => true, 'accommodations' => $result]);
    }

    /**
     * getAccommodationBooking
     * @param String $token
     * @param Tour $tour
     * returns JSON set of accommodation booking records for a tour
     */
    public function getAccommodationBooking(String $token, Tour $tour)
    {
        $bookings = new BookingRepository();
        $booking = $bookings->findBookingByToken($token);

        $traveller = new BookingTravellerRepository();
        $travellers = $traveller->getGroup($booking->id);
        $ids = $travellers->map(function($item, $key) {
            return $item->customer_id;
        });

        $accommodationRepository = new AccommodationRepository();
        $booked = $accommodationRepository->getAccommodationBooking($booking, $ids);

        return response()->json(["success" => true, 'bookings' => $booked]);
    }

    /**
     * loadRoomsForTour
     * @param Tour $tour
     * returns JSON rooms availble for a tour
     */
    public function loadRoomsForTour(Tour $tour) 
    {
        $rooms = AccommodationRepository::loadRoomsForTour($tour);

        return response()->json(["success" => true, 'rooms' => $rooms]);
    }

    /**
     * postAccommodationReservation
     * tour: id, event_id
     * customer_id is the key for order_customer
     * room_type: the ID of the room type desired
     * group: the label of the selected share group
     * @param Request $request
     * @return JSON: success on all records saved
     */
    public function postAccommodationReservation(Request $request)
    {
        $travellers = $request->travellers;
        $token = $request->token;
        $this->debug && Log::debug('postAccommodationReservation', [$token, $travellers]);
        // $bookings = new BookingRepository();
        // $booking = $bookings->findBookingByToken($token);

        $accommodationRepository = new AccommodationRepository();
        $status = [];
        foreach($travellers as $traveller) {
          $customer_id = isset($traveller['id']) ? $traveller['id'] : null;
          $room_type = isset($traveller['room_type']) ? $traveller['room_type'] : null;
          $group = isset($traveller['group']) ? $traveller['group'] : null;

          if (isset($customer_id) && isset($traveller['room_type'])) {
            $status[] = $accommodationRepository->makeAccommodationBooking([$token, $customer_id, $room_type, $group]);
          }
        }
        $status = in_array(false, $status);

        return response()->json(['success' => $status]);
    }

    /**
     * deleteAccommodationReservation - clears accommodation booking on reset
     *
     * @param Request $request
     * @return void
     */
    public function deleteAccommodationReservation(Request $request)
    {
      $token = $request->token;
      $bookings = new BookingRepository();
      $booking = $bookings->findBookingByToken($token);
      $this->debug && Log::debug('removing '.$token, [$booking]);
      $accommodationRepository = new AccommodationRepository();
      $accommodationRepository->remove($booking);

      return response()->json(['success' => true]);
    }

    /**
     * addAccommodationInventoryToTour (not booking-form API)
     *
     * @param Request $request
     * @param Tour $tour
     * @return void
     */
    public function addAccommodationInventoryToTour(Request $request, Tour $tour) {
        // TODO: Get actual enum values
        if ($request->has('type') && in_array($request->input('type'), ['Included', 'Add-on', 'Upgrade'])) {
            if ($request->has('ids')) {
                foreach ($request->input('ids') as $id) {
                    $inventory = AccommodationInventory::findOrFail($id);
                    $inventoryTour = AccommodationInventoryTour::make([
                        'accommodation_inventory_id' => $id,
                        'tour_component_type' => $request->input('type'),
                        'tour_sales_price' => $inventory->sales_price,
                    ]);
                    $tour->accommodationInventoryTours()->save($inventoryTour);
                }
            }
            TourRepository::autoAssignTemplating($tour);
            return response('Any listed components have been successfully added', 200);
        }
        abort(400, 'Invalid component type has been provided');
        return null;
    }

    public function saveRoomingData(Request $request, Order $order) {
        try {
            OrderRepository::buildGroupRooming($order, $request->data);
            return response('Building Saved', 200);
        } catch (RoomingFailedException $e) {
            abort(500, $e->getMessage());
        }
    }
}
