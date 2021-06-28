<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiController;
use App\Repository\ActionsRepository;
use App\Models\Tour;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrdersCustomer;
use App\Models\Accommodation;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;
use App\Models\BoardType;
use App\Models\CustomerOrderDetail;

class AccommodationController extends ApiController
{
    protected $component_type = 'accommodation';

    // this should get relational data for accomodation inventory
    public function getAccommodationInventoryData()
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
            ];
        })->toArray();

        return response()->json(["success" => true, "data" => $result]);
    }

    /**
     * getRoomAvailability for Tour
     * 
     *
     * @param Tour $tour
     * @return void
     */
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
        $inventory = new AccommodationInventory();
        $result = $inventory->select('accommodations.title', 'accommodation_inventories.*', 'room_types.room_type_name as room_type', 'room_types.maximum_occupancy')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('accommodation_inventory_tours', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('room_types', 'accommodation_inventories.room_type_id','room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();
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
            ->where('component_type', $this->component_type)
            ->where('reference', $token)
            ->whereNull('customer_order_details.deleted_at')
            ->get();

        if (!$result->count()) {
            // Log::info('getAccommodationBookingObject: No Customer Order Detail record found');
            return null;
        }
        // Log::info('Found: '.$result->count().'COD records: ref:'.$token.' for type '.$this->component_type, $result->toArray());
        return $result;
    }

    private function getAccommodationBookingForCustomer(Tour $tour, OrdersCustomer $ordersCustomer, $token)
    {
        // Log::info('getAccommodationBookingForCustomer Order: ', $ordersCustomer->toArray());
        $customer_order_detail = new CustomerOrderDetail();
        $result = $customer_order_detail
            ->where('orders_customer_id', $ordersCustomer->id)
            ->where('component_type', $this->component_type)
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
            ->where('component_type', $this->component_type)
            ->where('reference', $token)
            ->whereNull('customer_order_details.deleted_at')
            ->first();
        
        return $result;
    }

    public function getAccommodationBooking(Tour $tour, Order $order, $token)
    {
        Log::info('getAccommodationBooking');
        $orderCustomerIds = $this->findCustomersForOrder($order);
        $orderCustomers = new OrdersCustomer();
        $customerOrderDetails = $this->getAccommodationBookingObject($tour, $orderCustomerIds, $token);
        if (!$customerOrderDetails) {
            return response()->json(['success' => false, 'bookings' => NULL]);
        }
        foreach($customerOrderDetails as $booking) {
            Log::info('getAccommodationBooking', $booking->toArray());
            $booking->accommodation = AccommodationInventory::where('accommodation_inventories.id', $booking->inventory_id)
                ->join('room_types', 'accommodation_inventories.room_type_id','room_types.id')
                ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
                ->first();
            $booking->accommodation->details = Accommodation::find($booking->accommodation->accommodation_id);
            $orderCustomer = $orderCustomers->find($booking->orders_customer_id);
            $booking->customer = Customer::find($orderCustomer->customer_id);
        }
        return response()->json(["success" => true, 'bookings' => $customerOrderDetails]);
    }

    private function assignAccommodationBooking(CustomerOrderDetail &$customer_order_detail, $ordersCustomer, $reference, AccommodationInventory $accommodationInventory, AccommodationInventoryTour $accommodationInventoryTour)
    {
        $boardTypes = new BoardType();
        $boardType = $boardTypes->findOrFail($accommodationInventory->board_type_id);
        $customer_order_detail->component_type = $this->component_type;
        $customer_order_detail->orders_customer_id = $ordersCustomer->id;
        $customer_order_detail->inventory_tour_id = $accommodationInventoryTour->id;
        $customer_order_detail->type = $boardType->board_type_name;
        $customer_order_detail->date_time = date('Y-m-d H:i:s');
        $customer_order_detail->addon = 0;
        $customer_order_detail->cost = $accommodationInventory->sales_price;
        $customer_order_detail->reference = $reference;
        $customer_order_detail->order_id = $ordersCustomer->order_id;
        $customer_order_detail->inventory_id = $accommodationInventory->id;
        // Log::info('cod', $customer_order_detail->toArray());
        //return $customer_order_detail;
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
}
