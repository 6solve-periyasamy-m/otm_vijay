<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\Tour;
use App\Models\Accommodation;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrdersCustomer;
use App\Models\CustomerOrderDetail;

interface AccommodationRepositoryInterface {
    public function loadRoomsForTour(Tour $tour);
}

class AccommodationRepository implements AccommodationRepositoryInterface
{
    protected $model;

    // TODO: do we need to inject the model or just instantiate it?
    public function OLD__construct(Accommodation $model)
    {
        $this->model = $model;
    }

    public function __construct()
    {
        $this->model = new Accommodation();
    }

    public function getSettings(Tour $tour, $token)
    {
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
        $orderCustomers = OrdersCustomer::where('order_id', $order->id)
            ->orderBy('is_lead_booker', 'desc')
            ->orderBy('id')
            ->get();
        // get the customer
        foreach ($orderCustomers as &$orderCustomer) {
            $orderCustomer->customer = Customer::find($orderCustomer->customer_id);
            $this->logger(5, 'orderCustomer', $orderCustomer);
            $cod = new CustomerOrderDetail();
            // there should only be one record per orders_customer_id of a component type accommodation (error check?)
            $orderCustomer->booking = $cod->where('component_type', 'accommodation')
                ->where('orders_customer_id', $orderCustomer->id)
                ->first();
            $orderCustomer->booking->types = json_decode($orderCustomer->booking->type);
        }

        return $orderCustomer;
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
            'accommodations.title',
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id',
            'accommodation_inventories.*',
            'room_types.room_type_name as room_type',
            'room_types.maximum_occupancy')
            ->join('accommodations', 'accommodation_inventories.accommodation_id', 'accommodations.id')
            ->join('accommodation_inventory_tours', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();

        return $result;
    }

    public function getAccommodationBooking($customerOrderDetails)
    {
        $orderCustomers = new OrdersCustomer();
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
        return $customerOrderDetails;
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
        // $rooms = $tours->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id','accommodation_inventories.id')
        //             ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
        //             ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
        //             ->where('accommodation_inventory_tours.tour_id', $tour->id)
        //             ->get();
        $rooms = $tours->select(
            'accommodation_inventories.*',
            'accommodation_inventories.id as accommodation_inventory_id',
            'accommodation_inventory_tours.tour_id',
            'accommodation_inventory_tours.id as accommodation_inventory_tour_id',
            'room_types.id as room_type_id',
            'room_types.room_type_name',
            'room_types.maximum_occupancy',
            'board_types.id as board_type_id',
            'board_types.board_type_name'
        );
        $rooms = $rooms->join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', 'accommodation_inventories.id')
            ->join('room_types', 'accommodation_inventories.room_type_id', 'room_types.id')
            ->join('board_types', 'accommodation_inventories.board_type_id', 'board_types.id')
            ->where('accommodation_inventory_tours.tour_id', $tour->id)
            ->get();

        return $rooms;
    }
}
