<?php

namespace App\Http\Controllers\Api;
use Exception;
use App\Models\Tour;

use App\Models\Order;
use App\Models\Customer;
use App\Models\BoardType;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\OrderCustomer;
use App\Models\CustomerOrderDetail;
use Illuminate\Support\Facades\Log;
use App\Repository\ActionsRepository;
use App\Models\AccommodationInventory;
use App\Http\Controllers\ApiController;
use App\Models\AccommodationInventoryTour;
use App\Repository\CustomerOrderDetailRepository;

class AccommodationController extends ApiController
{
    protected $component_type = 'accommodation';
    private $debug = 5;

    private function logger($level, $message, ...$params) {
        if ($this->debug > $level) {
            Log::info($message, $params);
        }
    }

    // this should get relational data for accomodation inventory
    public function getAccommodationInventoryData()
    {
        $inventory = new AccommodationInventory();
        $result = $inventory->map(function ($accommodationInventory) {
            return [
                "id" => $accommodationInventory->id,
                "accommodation_id" => $accommodationInventory->accommodation->id,
                "check_in" => $accommodationInventory->check_in->format('Y-m-d H:i:s'),
                "check_out" => $accommodationInventory->check_out->format('Y-m-d H:i:s'),
                "accommodation_name" => $accommodationInventory->accommodation->name,
                "accommodation_address" => $accommodationInventory->accommodation->address,
                "room_type" => $accommodationInventory->roomType->name,
                "board_type" => $accommodationInventory->boardType->name,
                "booking_policy" => $accommodationInventory->booking_policy,
            ];
        })->toArray();

        return response()->json(["success" => true, "data" => $result]);
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
        $inventory = new AccommodationInventory();
        $resultOLD = $inventory->select('accommodations.title', 'accommodation_inventories.*', 'room_types.name as room_type', 'room_types.maximum_occupancy')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('accommodation_inventory_tours', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('room_types', 'accommodation_inventories.room_type_id','room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();
        $result = $inventory->select('accommodations.title', 
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id', 
            'accommodation_inventories.*', 
            'room_types.name as room_type',
            'room_types.maximum_occupancy')
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
        $orderCustomers = OrderCustomer::where('order_id', $order->id)
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
            ->whereIn('order_customer_id', $orderCustomerIds)
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

    private function getAccommodationBookingForCustomer(Tour $tour, OrderCustomer $orderCustomer, $token)
    {
        // Log::info('getAccommodationBookingForCustomer Order: ', $orderCustomer->toArray());
        $customer_order_detail = new CustomerOrderDetail();
        $result = $customer_order_detail
            ->where('order_customer_id', $orderCustomer->id)
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
        $orderCustomers = new OrderCustomer();
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
            $orderCustomer = $orderCustomers->find($booking->order_customer_id);
            $booking->customer = Customer::find($orderCustomer->customer_id);
        }
        return response()->json(["success" => true, 'bookings' => $customerOrderDetails]);
    }

    public function loadRoomsForTour(Tour $tour, $order_id) {
        /*
            select ait.tour_id,`name`, maximum_occupancy, name, stock, ai.sales_price, ait.sales_price as tour_sales_price, ai.booking_policy
            from accommodation_inventory_tours ait 
            join accommodation_inventories ai on ait.accommodation_inventory_id=ai.id
            join room_types rt on rt.id=ai.room_type_id
            join board_types bt on bt.id=ai.board_type_id
            where tour_id=2
        */
        $tours = new AccommodationInventoryTour();
        // $rooms = $tours->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id','accommodation_inventories.id')
        //             ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
        //             ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
        //             ->where('accommodation_inventory_tours.tour_id', $tour->id)
        //             ->get();
        $rooms = $tours->select('accommodation_inventories.*',
            'accommodation_inventories.id as accommodation_inventory_id',
            'accommodation_inventory_tours.tour_id',
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id', 
            'room_types.id as room_type_id', 
            'room_types.name',
            'room_types.maximum_occupancy',
            'board_types.id as board_type_id', 
            'board_types.name');
        $rooms = $rooms->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id','accommodation_inventories.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();
        Log::info( 'rooms for tour', $rooms->toArray());

        return response()->json(["success" => true, 'rooms' => $rooms]);
    }

    private function assignAccommodationBooking(CustomerOrderDetail &$customer_order_detail, $orderCustomer, $reference, AccommodationInventory $accommodationInventory, AccommodationInventoryTour $accommodationInventoryTour)
    {
        $boardTypes = new BoardType();
        $boardType = $boardTypes->findOrFail($accommodationInventory->board_type_id);
        $customer_order_detail->component_type = $this->component_type;
        $customer_order_detail->order_customer_id = $orderCustomer->id;
        $customer_order_detail->inventory_tour_id = $accommodationInventoryTour->id;
        $customer_order_detail->type = $boardType->name;
        $customer_order_detail->date_time = date('Y-m-d H:i:s');
        $customer_order_detail->addon = 0;
        $customer_order_detail->cost = $accommodationInventory->sales_price;
        $customer_order_detail->reference = $reference;
        //$customer_order_detail->order_id = $orderCustomer->order_id;
        $customer_order_detail->inventory_id = $accommodationInventory->id;
        // Log::info('cod', $customer_order_detail->toArray());
        //return $customer_order_detail;
    }

    private function getCustomerOrder($order_id, $customer_id)
    {
        // get the customer order record
        $customerOrder = new OrderCustomer();
        Log::info('getting customerOrder', [$order_id, $customer_id]);
        try {
            $customerOrders = $customerOrder
                ->where('order_id', $order_id)
                ->where('id', $customer_id)
                ->get();
        } catch (Exception $e) {
            Log::info('error' . $e->getMessage());
            die('fail');
        }

            // if ($customerOrders->count() > 1) {
        //     Log::info('WARNING: postAccommodationReservation found more than one record for customer '.$traveller->customer_id.' order '.$order_id);
        // }

        if ($customerOrders->count() === 1) {
            $customerOrder = $customerOrders[0];
        } else {
            Log::info('wtf? '.  $order_id . ', c='. $customer_id);
        }
        return $customerOrder;
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
        // confirm order for this tour is active
        $orders = Order::where('tour_id', $tour->id)
            ->where('token', $token)
            ->where('order_status', '1')
            ->whereNull('deleted_at')
            ->get();
        // did we find an order?
        if ($orders === 0) {
            return response()->json(['success' => false, 'message' => 'no active order']);
        }
        if ($this->debug>4) {
            Log::info('getAccommodationSettings #' . $orders->count(), $orders->toArray());
        }
        // each token must be unique or something is horribly wrong
        if ($orders->count() !== 1) {
            return response()->json(['success' => false, 'message' => 'duplicated order']);
        }
        $order = $orders[0];
        // get all the customers for this order
        $orderCustomers = OrderCustomer::where('order_id', $order->id)
            ->orderBy('is_lead_booker', 'desc')
            ->orderBy('id')
            ->get();
        // get the customer
        foreach ($orderCustomers as &$orderCustomer) {
            $orderCustomer->customer = Customer::find($orderCustomer->customer_id);
            $this->logger(5, 'orderCustomer', $orderCustomer);
            $cod = new CustomerOrderDetail();
            // there should only be one record per order_customer_id of a component type accommodation (error check?)
            $orderCustomer->booking = $cod->where('component_type', 'accommodation')
                ->where('order_customer_id', $orderCustomer->id)
                ->first();
            $orderCustomer->booking->types = json_decode($orderCustomer->booking->type);
        }

        $this->logger(5, 'getAccommodationSettings logger for customers', $orderCustomers);
        return response()->json(['success' => true, 'travellers' => $orderCustomers]);
    }

    //public function updateAccommodationReservation($order_id, $tour, $room, $customer_id, $sharer_id) 
    public function updateAccommodationReservation(
        $order_id,
        $room,
        $traveller,
        $customer_id,
        $reference)
    {
        $customerOrder = $this->getCustomerOrder($order_id, $customer_id);
        if (!$customerOrder) {
            throw new \Exception('Missing Customer Order!');
        }
        $customerOrderId = $customerOrder->id;

        $type = json_encode(['room' => $room, 'shared' => $traveller['shared'], 'shares' => $traveller['shares']]);
        $inventoryTourId = isset($room['accommodation_inventory_tour_id']) ? $room['accommodation_inventory_tour_id'] : 0;
        // only save the booking record, the share records are not required in COD\
        if ($inventoryTourId) {
            $COD = new CustomerOrderDetailRepository();
            $existing = $COD->getCOD($customerOrderId , $this->component_type);
            if ($existing->count()) {
                $cod = $existing[0];
                $cod = new CustomerOrderDetail();
                $cod->status = 'update';
                $COD->purge($customerOrderId, $this->component_type);
            } else {
                $cod = new CustomerOrderDetail();
                $cod->status = 'created';
            }
            $COD->saveCOD($cod, $customerOrderId, $this->component_type, $inventoryTourId, $traveller, $reference, $type);
        } else {
            Log::info('not saving for inventoryTourId: '. $inventoryTourId);
        }

        return $cod;
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
        if ($this->debug) {
            Log::info('post', [$tour, $order_id, $customer_id]);
            Log::info('room', [$room]);
            Log::info('shares', $shares);
            Log::info('traveller', $traveller);
            Log::info('reference '. $reference);
            // Log::info('sharers', $traveller->shares);
        }
        $results[] = $this->updateAccommodationReservation(
            $order_id,
            $room,
            $traveller,
            $customer_id,
            $reference
        );

        return response()->json(['success' => true, 'data' => $results]);
    }

    public function postAccommodationBooking(Tour $tour, OrderCustomer $orderCustomer, String $reference, AccommodationInventory $accommodationInventory, Order $order)
    {
        Log::info('post accommodation booking', $orderCustomer->toArray());
        // Log::info('post accommodation booking', $accommodationInventory->toArray());

        $token = $_COOKIE['OTM_booking_order_token'];
        if ($token !== $reference) {
            ActionsRepository::log('Token mismatch', $orderCustomer->customer_id, $orderCustomer->order_id, $reference, 'token cookie '. $token);
        }
        
        $accomodationInventoryTours = new AccommodationInventoryTour();
        $accommodationInventoryTour = $accomodationInventoryTours->where('tour_id', $tour->id)
            ->where('accommodation_inventory_id', $accommodationInventory->id)
            ->first();

        //$orderCustomer = Ordercustomer::where('customer_id', $customer->id)->where('order_id', $order->id)->firstOrFail();
        ActionsRepository::log('Accommodation Booking', $orderCustomer->customer_id, $orderCustomer->order_id, $reference, 'Customer Order '.$orderCustomer->id . ' for tour '.$tour->name);
        
        $customer_order_detail = $this->getAccommodationBookingForCustomer($tour, $orderCustomer, $reference);
        if (isset($customer_order_detail)) {
            Log::info('getAccommodationBookingForCustomr returned ' . $customer_order_detail->count());
        } else {
            Log::info('getAccommodationBookingForCustomr returned NOTHING');
        }
    
        if (!$customer_order_detail) {
            // Log::info('postAccommodationBooking Create', $orderCustomer->toArray());
            $customer_order_detail = new CustomerOrderDetail();
            $customer_order_detail->status = 'created';
            $this->assignAccommodationBooking($customer_order_detail, $orderCustomer, $reference, $accommodationInventory, $accommodationInventoryTour, $order->id);
            $customer_order_detail->save();
            // Log::info('ACCOMMODATION saving single COD ', $customer_order_detail->toArray());
        } else {
            // Log::info('postAccommodationBooking Update', $customer_order_detail->toArray());
            $customer_order_detail->status = 'updated';
            $this->assignAccommodationBooking($customer_order_detail, $orderCustomer, $reference, $accommodationInventory, $accommodationInventoryTour, $order->id);
            $customer_order_detail->save();
            // Log::info('ACCOMMODATION saving COD ', $customer_order_detail->toArray());
        }

        return response()->json(["success" => true, "data" => $customer_order_detail]);
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
