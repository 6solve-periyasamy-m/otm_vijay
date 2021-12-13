<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiController;

use App\Models\Tour;
use App\Models\OrderCustomer;
use App\Models\CustomerOrderDetail;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;

use App\Repository\AccommodationRepository;
use App\Repository\AdditionalTravellerRepository;
use App\Repository\BookingRepository;

class AccommodationController extends ApiController
{
    protected $component_type = 'accommodation';
    private $debug = 4;

    /***
     * this tour has a range of accommodation options: ??? deprecated
     * 
     */
    public function getAccommodationOptions(Tour $tour)
    {
        Log::info('getAccommodationOptions');
        return response()->json(["success" => true, 'options' => $tour]);
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

        Log::info('getAccommodationInventoryForTour', $result->toArray());

        return response()->json(["success" => true, 'accommodations' => $result]);
    }


    private function getAccommodationBookingForCustomer(Tour $tour, OrderCustomer $orderCustomer, $token)
    {
        // Log::info('getAccommodationBookingForCustomer Order: ', $orderCustomer->toArray());
        $customer_order_detail = new CustomerOrderDetail();
        $result = $customer_order_detail
            ->where('type', $this->component_type)
            ->where('order_customer_id', $orderCustomer->id)
            ->where('reference', $token)
            ->whereNull('customer_order_details.deleted_at')
            ->first();
        
        return $result;
    }

    public function getAccommodationBooking(String $token, Tour $tour)
    {
        $bookings = new BookingRepository();
        $booking = $bookings->findBookingByToken($token);
        $traveller = new AdditionalTravellerRepository();

        $travellers = $traveller->getGroup($booking->id);
        $ids = $travellers->map(function($item, $key) {
            return $item->customer_id;
        });

        $accommodationRepository = new AccommodationRepository();
        $booked = $accommodationRepository->getAccommodationBooking($booking, $ids);

        return response()->json(["success" => true, 'bookings' => $booked]);
    }

    public function loadRoomsForTour(Tour $tour) {

        $repo = new AccommodationRepository();
        $rooms = $repo->loadRoomsForTour($tour);

        return response()->json(["success" => true, 'rooms' => $rooms]);
    }

    /**
     * postAccommodationReservation
     * accommodation is reserved loosely: it is more of a booking plan than actual reservation
     * each member of the tour party can declare who they share with (or are included as one of the sharers)
     * with an intended room type
     * tour: id, event_id
     * traveller: customer_id, order_id, room, shared, shares
     * customer_id is the key for order_customer
     * room: accommodation_inventory_id, board_type_id/_name, check_in, maximum_occupancy, 
     * shared: { traveller_id: shares[names]}
     * shares: [[IDs (match with names)]]
     * Create a COD record but associate a secondary record for accommodation intent
     * customer_order_details_id
     * @param Request $request
     * @return void
     */
    public function postAccommodationReservation(Request $request)
    {
        $traveller = $request->traveller;
        $accommodation_inventory_tour_id = $request->accommodation_inventory_tour_id;
        $booking_token = $request->token;
        $bookings = new BookingRepository();
        $booking = $bookings->findBookingByToken($booking_token);

        $customer_id = $traveller['customer_id'];
        $room = isset($traveller['room']) ? $traveller['room'] : null;
        $shares = [$traveller['shares']];
        foreach ($shares as $key => $value) {
            $shared[$key] = $shares[$key];
        }

        $accommodationRepository = new AccommodationRepository();
        $results = $accommodationRepository->updateAccommodationBooking($booking, $room, $traveller, $customer_id, $accommodation_inventory_tour_id);
 
        return response()->json(['success' => true, 'accommodation' => $results]);
    }

    public function deleteAccommodationReservation(Request $request)
    {
        $groupIds = $request->groupIds;
        $inventoryTourIds = $request->inventoryTourIds;
        $tour_id = $request->tour_id;
        Log::info('deleting Accommodation Reservation for group', $groupIds);
        Log::info('inventory Tour IDs', $inventoryTourIds);
        Log::info('delete for tour '. $tour_id);
        $accommodationRepository = new AccommodationRepository();
        $accommodationRepository->remove($inventoryTourIds, $groupIds);
        Log::info('deleteAccommodationReservation'); //, $group);
    }

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
            return response('Any listed components have been successfully added', 200);
        }
        abort(400, 'Invalid component type has been provided');
        return null;
    }
}
