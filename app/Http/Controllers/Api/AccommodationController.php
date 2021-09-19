<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiController;

use App\Models\Tour;
use App\Models\Order;
use App\Models\Customer;
use App\Models\BoardType;
// use App\Models\Accommodation;
use App\Models\OrdersCustomer;
use App\Models\CustomerOrderDetail;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;

use App\Repository\ActionsRepository;
// use App\Repository\CustomerOrderDetailRepository;
use App\Repository\AccommodationRepository;
// use App\Repository\OrdersCustomerRepository;

class AccommodationController extends ApiController
{
    protected $component_type = 'accommodation';
    private $debug = 4;

    // // this should get relational data for accomodation inventory
    // public function getAccommodationInventoryData()
    // {
    //     $accommodationRepository = new AccommodationRepository();
    //     $result = $accommodationRepository->getAccommodationInventoryData();

    //     return response()->json(["success" => true, "data" => $result]);
    // }

    /***
     * this tour has a range of accommodation options: 
     * 
     */
    public function getAccommodationOptions(Tour $tour)
    {
        Log::info('getAccommodationOptions');
        return response()->json(["success" => true, 'options' => $tour]);
    }

    public function getAccommodationSettings(Tour $tour)
    {
        $token = $_COOKIE['OTM_booking_order_token'];
        if ($this->debug>3) {
            Log::info('getAccommodationSettings for tour'. $tour->id. ' by ' . $token);
        }
        if (strlen($token) !== 32) {
            return response()->json(['success' => false, 'message' => 'invalid key']);
        }

        $accommodationRepository = new AccommodationRepository();
        $orderCustomers = $accommodationRepository->getSettings($tour, $token);

        $accommodationRepository->logger(5, 'getAccommodationSettings logger for customers', $orderCustomers);
        return response()->json(['success' => true, 'travellers' => $orderCustomers]);
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

    private function findCustomersForOrder(Order $order) {
        $orderCustomers = OrdersCustomer::where('order_id', $order->id)
            ->get();

        $orderCustomerIds = [];
        foreach ($orderCustomers as $orderCustomer) {
            $orderCustomerIds[] = $orderCustomer->id;
        }
        return $orderCustomerIds;
    }

    private function getAccommodationBookingObject(Tour $tour, $orderCustomerIds, $token)
    {
        $customer_order_detail = new CustomerOrderDetail();
        $result = $customer_order_detail
            ->whereIn('orders_customer_id', $orderCustomerIds)
            ->where('type', $this->component_type)
            ->where('reference', $token)
            ->whereNull('customer_order_details.deleted_at')
            ->get();

        if (!$result->count()) {
            // Log::info('getAccommodationBookingObject: No Customer Order Detail record found');
            return null;
        }
        Log::info('Found: '.$result->count().'COD records: ref:'.$token.' for type '.$this->component_type, $result->toArray());

        return $result;
    }

    private function getAccommodationBookingForCustomer(Tour $tour, OrdersCustomer $ordersCustomer, $token)
    {
        // Log::info('getAccommodationBookingForCustomer Order: ', $ordersCustomer->toArray());
        $customer_order_detail = new CustomerOrderDetail();
        $result = $customer_order_detail
            ->where('orders_customer_id', $ordersCustomer->id)
            ->where('type', $this->component_type)
            ->where('reference', $token)
            ->whereNull('customer_order_details.deleted_at')
            ->first();
        
        return $result;
    }

    private function getAccommodationBookingForOrder(Tour $tour, Order $order, $token)
    {
        // Log::info('getAccommodationBookingForOrder  order: ', $order->toArray());
        $customer_order_detail = new CustomerOrderDetail();
        $result = $customer_order_detail
            ->where('order_id', $order->id)
            ->where('type', $this->component_type)
            ->where('reference', $token)
            ->whereNull('customer_order_details.deleted_at')
            ->first();
        
        return $result;
    }

    // private function findAccommodationBookingFor(Customer $customer, $tour)
    // {
    //     Log::info('findAccommodationBookingFor:', [$customer, $tour]);
    // }


    public function getAccommodationBooking($tour_id, $order_id, $token, $travellers)
    {
        $order = Order::findOrFail($order_id);
        $traveller_ids = explode(',', $travellers);
        $tour = Tour::findOrFail($tour_id);

        // establish Tour $tour, Order $order, $token
        $orderCustomerIds = $this->findCustomersForOrder($order);

        // Log::info('getAccommodationBooking customer ids', $orderCustomerIds);
        // Log::info('getAccommodationBooking traveller ids', $traveller_ids);

        $accommodationRepository = new AccommodationRepository();
        $customerOrderDetails = $accommodationRepository->getAccommodationBooking($orderCustomerIds);

        // $customerOrderDetails = $this->getAccommodationBookingObject($tour, $orderCustomerIds, $token);
        // if (!$customerOrderDetails) {
        //     return response()->json(['success' => false, 'bookings' => NULL]);
        // }
Log::info('getAccommodationBooking', $customerOrderDetails->toArray());

        return response()->json(["success" => true, 'bookings' => $customerOrderDetails]);
    }

    public function loadRoomsForTour(Tour $tour, $order_id) {

        $repo = new AccommodationRepository();
        $rooms = $repo->loadRoomsForTour($tour);

        return response()->json(["success" => true, 'rooms' => $rooms]);
    }

    private function assignAccommodationBooking(CustomerOrderDetail &$customer_order_detail, $ordersCustomer, $reference, AccommodationInventory $accommodationInventory, AccommodationInventoryTour $accommodationInventoryTour)
    {
        $boardTypes = new BoardType();
        $boardType = $boardTypes->findOrFail($accommodationInventory->board_type_id);
        $customer_order_detail->type = $this->component_type;
        $customer_order_detail->orders_customer_id = $ordersCustomer->id;
        $customer_order_detail->inventory_tour_id = $accommodationInventoryTour->id;
       // $customer_order_detail->type = $boardType->board_type_name;
        $customer_order_detail->date_time = date('Y-m-d H:i:s');
        $customer_order_detail->addon = 0;
        $customer_order_detail->cost = $accommodationInventory->sales_price;
        $customer_order_detail->reference = $reference;
        $customer_order_detail->inventory_id = $accommodationInventory->id;
    }

    /**
     * postAccommodationReservation
     * accommodation is reserved loosely: it is more of a booking plan than actual reservation
     * each member of the tour party can declare who they share with (or are included as one of the sharers)
     * with an intended room type
     * tour: id, event_id
     * traveller: customer_id, order_id, room, shared, shares
     * customer_id is the key for order_customer
     * room: accommodation_inventory_id, board_type_id/_name, check_in_date_time, maximum_occupancy, 
     * shared: { traveller_id: shares[names]}
     * shares: [[IDs (match with names)]]
     * Create a COD record but associate a secondary record for accommodation intent
     * customer_order_details_id
     * @param Request $request
     * @return void
     */
    public function postAccommodationReservation(Request $request)
    {
        $tour = $request->tour;
        $traveller = $request->traveller;

        $order_id = $traveller['order_id'];
        $customer_id = $traveller['customer_id'];
        $reference = $request->reference;

        $room = isset($traveller['room']) ? $traveller['room'] : null;
        $shares = [$traveller['shares']];
        foreach ($shares as $key => $value) {
            $shared[$key] = $shares[$key];
        }

        Log::info('post', [$tour, $order_id, $customer_id]);
        Log::info('room', [$room]);
        Log::info('shares', $shares);
        Log::info('traveller', $traveller);
        Log::info('reference '. $reference);

        $accommodationRepository = new AccommodationRepository();

        $results[] = $accommodationRepository->updateAccommodationReservation(
            $order_id,
            $room,
            $traveller,
            $customer_id,
            $reference
        );

        return response()->json(['success' => true, 'accommodation' => $results]);
    }

    public function postAccommodationBooking(Tour $tour, OrdersCustomer $ordersCustomer, String $reference, AccommodationInventory $accommodationInventory, Order $order)
    {
        Log::info('post accommodation booking', $ordersCustomer->toArray());
        // Log::info('post accommodation booking', $accommodationInventory->toArray());

        $token = $_COOKIE['OTM_booking_order_token'];
        if ($token !== $reference) {
            ActionsRepository::log('Token mismatch', $ordersCustomer->customer_id, $ordersCustomer->order_id, $reference, 'token cookie '. $token);
        }
        
        $accomodationInventoryTours = new AccommodationInventoryTour();
        $accommodationInventoryTour = $accomodationInventoryTours->where('tour_id', $tour->id)
            ->where('accommodation_inventory_id', $accommodationInventory->id)
            ->first();

        //$ordersCustomer = OrdersCustomer::where('customer_id', $customer->id)->where('order_id', $order->id)->firstOrFail();
        ActionsRepository::log('Accommodation Booking', $ordersCustomer->customer_id, $ordersCustomer->order_id, $reference, 'Customer Order '.$ordersCustomer->id . ' for tour '.$tour->title);
        
        $customer_order_detail = $this->getAccommodationBookingForCustomer($tour, $ordersCustomer, $reference);
        if (isset($customer_order_detail)) {
            Log::info('getAccommodationBookingForCustomr returned ' . $customer_order_detail->count());
        } else {
            Log::info('getAccommodationBookingForCustomr returned NOTHING');
        }
    
        if (!$customer_order_detail) {
            // Log::info('postAccommodationBooking Create', $ordersCustomer->toArray());
            $customer_order_detail = new CustomerOrderDetail();
            $customer_order_detail->status = 'created';
            $this->assignAccommodationBooking($customer_order_detail, $ordersCustomer, $reference, $accommodationInventory, $accommodationInventoryTour, $order->id);            
            $customer_order_detail->save();
            // Log::info('ACCOMMODATION saving single COD ', $customer_order_detail->toArray());
        } else {
            // Log::info('postAccommodationBooking Update', $customer_order_detail->toArray());
            $customer_order_detail->status = 'updated';
            $this->assignAccommodationBooking($customer_order_detail, $ordersCustomer, $reference, $accommodationInventory, $accommodationInventoryTour, $order->id);
            $customer_order_detail->save();
            // Log::info('ACCOMMODATION saving COD ', $customer_order_detail->toArray());
        }

        return response()->json(["success" => true, "data" => $customer_order_detail]);
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
}
