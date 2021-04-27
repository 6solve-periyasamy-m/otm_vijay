<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\Tour;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Order;
use App\Models\Customer;
use App\Models\FlightInventoryTour;
use App\Models\FlightInventory;
use App\Models\CustomerOrderDetails;
use App\Models\OrdersCustomer;
use App\Models\PaymentSchedule;
// use App\Models\PaymentInstallment;
// use App\Repository\FlightsRepository;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public $logging = false;

    // Open API - populate the booking form selectors
    public function getEvents() {
        $events = Event::where('event_start_date', '>', date('Y-m-d'))->get();

        return response()->json(['success' => true, 'data' => $events->toArray()]);
    }

    public function getTours($event_id = null) {
        $today = date('Y-m-d');
        if ($event_id) {
            $tours = Tour::where('event_id', $event_id)
                        ->get();
        } else {
            $tours = Tour::get();
        }

        return response()->json(['success' => true, 'data' => $tours->toArray()]);
    }

    public function getAirlines() 
    {
        $airlines = Airline::orderBy('airline_name')->get();

        return response()->json(["success" => true, "data" => $airlines->toArray()]);
    }


    public function getAirports($location_id = null, $region_id = null) 
    {
        $airport = new Airport();
        $airport = $airport->select('airports.*')
                        ->join('locations', 'location_id', 'locations.id')
                        ->join('regions', 'locations.region_id', 'regions.id');
        if (isset($location_id)) {
            $airport = $airport->where('location_id', $location_id);
        }
        if (isset($region_id)) {
            $airport = $airport->where('region_id', $region_id);
        }

        $airports = $airport->orderBy('airport_name', 'asc')->get()->toArray();
        $airports = array_combine(array_column($airports,'id'),$airports);

        return response()->json(["success" => true, "airports" => $airports]);
    }

    public function getFlightInventories()
    {
        $flights = Flight::join('airlines', 'airline_id', 'airlines.id')
            ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
            ->get();

        return response()->json(["success" => true, "data" => $flights->toArray()]);
    }

    /***
     * flights booked for a tour create records in the flight_inventory_tours table
     * these associate a flight_inventory_id with a tour_id (so the tour booking creates these)
     */
    public function getFlightInventoriesForTour($tour_id, $flight_type = null)
    {
        $flight = new Flight();
        // $flightsRepository = new FlightsRepository($flight);
        // $flights = $flightsRepository->flights($tour_id);

        $flights = Flight::select('flight_inventories.*', 'flight_inventory_tour.id as flight_inventory_tour_id', 'flight_inventory_tour.flight_type', 'flights.departure_airport_id', 'flights.arrival_airport_id', 'airlines.airline_name', 'travel_classes.title as travel_class')
            ->join('airlines', 'airline_id', 'airlines.id')
            ->join('flight_inventories', 'flight_inventories.flight_id', 'flights.id')
            ->join('travel_classes', 'flight_inventories.travel_class_id', 'travel_classes.id')
            ->join('flight_inventory_tour', 'flight_inventory_tour.flight_inventory_id', 'flight_inventories.id')
            ->where('flight_inventory_tour.tour_id', $tour_id);

        if (isset($flight_type) && strlen($flight_type)) {
            $flights = $flights->where('flight_inventory_tour.flight_type', $flight_type);
        } else {
            $flights = $flights->whereIn('flight_inventory_tour.flight_type', ['Outbound', 'Inbound'])
                ->orderBy('flight_inventory_tour.flight_type', 'desc');
        }
        
        if ($this->logging) \Log::info('flights  type:' . $flight_type .' tour_id:'.  $tour_id . ' : '. $flights->toSql());

        $flights = $flights 
            ->orderBy('airlines.airline_name', 'asc')
            ->get();

        return response()->json(["success" => true, "data" => $flights->toArray()]);

    }

    public function getPaymentSchedules()
    {
        $schedules = PaymentSchedule::orderBy('title')->get();

        return response()->json(["success" => true, "schedules" => $schedules->toArray()]);
    }

    public function getPaymentSchedule($id) 
    {
        $schedule = PaymentSchedule::findOrFail($id);
        if ($this->logging) \Log::debug('schedule for id '.$id, $schedule->toArray());
        return response()->json(["success" => true, "schedule" => $schedule->toArray()]);
    }

    public function getFlightsFromAirport(Airport $airport = null)
    {
        // Returns a list of flights from an airport
        $today = date('Y-m-d');
        $flights = Flight::where('departure_airport_id', $airport->id)
            ->orWhere(function($query) {
                $query->whereNull('departure_date')
                    ->where(DB::raw("(STR_TO_DATE(flights.departure_date,'%y-%m-%d'))"), ">=", date('Y-m-d'));
            })
            ->get();
        $result = $flights->map(function ($flight) {
            return [
                "id" => $flight->id,
                "departure_airport_id" => $flight->departure_airport_id,
                "departure_date" => $flight->departure_date,
                "arrival_airport_id" => $flight->arrival_airport_id,
                "arrival_date" => $flight->arrival_date,
            ];
        })->toArray();

        return response()->json(["success" => true, "data" => $result]);
    }

    // Autheticated API - return data for logged in user sessions
    public function getBasicTourInformation(Tour $tour)
    {
        $tour = Tour::findOrFail($tour->id);

        return response()->json([
            "success" => true,
            "title" => $tour->title,
            "description" => $tour->description,
            "base_price_per_person" => $tour->base_price_per_person,
            // tour_colour - is an ID so i'm assuming there would be a relationship, doesn't exist yet
            // tour_merchandise - is an ID so i'm assuming there would be a relationship, doesn't exist yet


            // This is just a basic start with the models that I have access to and the relationships I currently have
        ]);
    }

    public function getFlightsFromTour(Tour $tour)
    {
        // TODO: As above, add authentication.
        // You don't want scrapers just scraping all of the information out of the DB from these APIs
        $inventory = $tour->flightInventory;
        $result = $inventory->map(function ($flightInventory) {
            return [
                "id" => $flightInventory->id,
                "flight_id" => $flightInventory->flight->id,
                "check_in_date_time" => $flightInventory->check_in_date_time,
                "departure_date_time" => $flightInventory->departure_date_time,
                "arrival_date_time" => $flightInventory->arrival_date_time,
                "class" => $flightInventory->travelClass->title,
                "airline" => $flightInventory->flight->airline->airline_name,
                "departure_airport" => $flightInventory->flight->departureAirport->airport_name,
                "arrival_airport" => $flightInventory->flight->arrivalAirport->airport_name,
            ];
        })->toArray();
        return response()->json(["success" => true, "data" => $result]);
    }

    public function getAccommodationFromTour(Tour $tour) // would use route model binding
    {
        $inventory = $tour->accommodationInventory;
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
     * createOrder - makes an order every time booking form is accessed by URL, unless it already exists (via token or link)
     *
     * @param Request $request
     * @return JSON (order object)
     */
    public function createOrder(Request $request)
    {
        $order = new Order();
        $order->quote_id = null;
        $order->tour_id = $request->tour;
        $order->total_order_value = null;
        $order->notes = 'Created by '.$_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI'];
        $order->order_status_id = 1;
        $order->token = md5(uniqId());
        $order->save();
        // Order::insert([
        //     'tour_id' => $order->tour_id, 
        //     'notes' => $order->notes,
        //     'token' => $order->token]);
        if ($this->logging) {
            \Log::info('create order for tour ' . $request->tour);
            \Log::info('order id ', $order->toArray());
        }
        return response()->json(["success" => true, "order" => $order]);
    }

    /**
     * saveCustomerDetails
     *
     * @param [type] $request
     * @param boolean $isLead
     * @return JSON (Customer object)
     */
    private function saveCustomerDetails($request, $isLead = false) 
    {
        $customer = new Customer();
        //does this customer already exist?
        $customerExists = $customer->where('email_address', $request->email_address)->first();
        if ($customerExists) {
            if ($this->logging) {
                \Log::info('customer exists record ', $customerExists->toArray());
            }
            $customer = $customerExists;
        } else {
            $customer->email_address = $request->email_address;
        }
        $customer->title = $request->title;
        $customer->first_name = $request->first_name;
        $customer->middle_names = $request->middle_names;
        $customer->last_name = $request->last_name;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->mobile_number = $request->mobile_number;
        $customer->other_phone_number = $request->other_phone_number;
        $customer->password = null;
        $customer->gender = $request->gender;
        if ($isLead) {
            $customer->address_line_1 = $request->address_line_1;
            $customer->address_line_2 = $request->address_line_2;
            $customer->address_line_3 = $request->address_line_3;
            $customer->billing_line_1 = isset($request->billing_line_1) ? $request->billing_line_1 : $request->address_line_1;
            $customer->billing_line_2 = isset($request->billing_line_2) ? $request->billing_line_2 : $request->address_line_2;
            $customer->billing_line_3 = isset($request->billing_line_3) ? $request->billing_line_3 : $request->address_line_3;
            $customer->town = $request->town;
            $customer->country = $request->country;
            $customer->postcode = $request->postcode;
            $customer->billing_town = isset($request->billing_town) ? $request->billing_town : $request->town;
            $customer->billing_country = isset($request->billing_country) ? $request->billing_country : $request->country;
            $customer->billing_postcode = isset($request->billing_postcode) ? $request->billing_postcode : $request->postcode;
        }
        if ($this->logging) {
            \Log::info('saving customer detaisl ', $customer->toArray());
        }
        $customer->save();

        return $customer;
    }

    private function updateEmergencyContactDetails(Request $request) 
    {
        return false;
    }

    /**
     * updateOrderCustomer - adds fields to existing orderCustomer record for a single traveller
     *
     * @param [type] $ordersCustomer (object)
     * @param Request $request
     * @return void
     */
    private function updateOrderCustomerFields($ordersCustomer, Request $request) 
    {
        if (!empty($request->tour['base_price_per_person'])) {
            $ordersCustomer->tour_cost = $request->tour['base_price_per_person'];
        }
        if (!empty($request->tour['single_occupancy_surcharge'])) {
            $ordersCustomer->single_occupancy_surcharge = $request->tour['single_occupancy_surcharge'];
        }
    }
    
    /**
     * saveOrderCustomer - stores the orderCustomer data from the booking form
     *
     * @param Customer $customer
     * @param Request $request
     * @param boolean $isLead
     * @return JSON (record saved)
     */
    private function saveOrderCustomer(Customer $customer, Request $request, $isLead = false) 
    {
        if (empty($request->order_id)) {
            throw new \Exception('SaveOrderCustomer has no order ID');
        }
        $ordersCustomer = new OrdersCustomer();
        $ordersCustomerExists = $ordersCustomer->where('order_id', $request->order_id)->where('customer_id', $request->customer_id)->first();
        if ($ordersCustomerExists) {
            $ordersCustomer = $ordersCustomerExists;
            $this->updateOrderCustomerFields($ordersCustomer, $request);
        } else {
            $ordersCustomer->order_id = $request->order_id;
            $ordersCustomer->customer_id = $customer->id;
        }
        $ordersCustomer->is_lead_booker = $isLead;
        $ordersCustomer->travel_insurer = null;
        $ordersCustomer->policy_number = null;
        $ordersCustomer->save();

        return $ordersCustomer;
    }

    /**
     * bookFlightDetails
     * save flight details for a pax tour flight
     * @param tour
     * @param passenger
     * @param flight (we are sending in the flight->id - which should be the flightInventoryTour record id)
     */
    public function bookFlightDetails($customer_id, $tour_id, $order_id, $flight_type, $flight_inventory_tour_id)
    {
        $customers = new Customer();
        $tours = new Tour();
        $flights = new Flight();
        $flightInventory = new FlightInventory();
        $flightTours = new FlightInventoryTour();
        $orders = new Order();
\Log::info('bookFlightDetails', [$customer_id, $tour_id, $order_id, $flight_type, $flight_inventory_tour_id]);
        $flightTour = $flightTours->find($flight_inventory_tour_id);
\Log::info('flightTour', $flightTour->toArray());
        $tour = $tours->find($tour_id);
        $order = $orders->find($order_id);
        $rejection = 0;
        if ($tour->id !== $order->tour_id) {
            $rejection = 302;
            $status = 'Invalid Order';
        } else {
            $status = 'Order and Tour agree';
        }
        $orderCustomers = new OrdersCustomer();
        $customer_id = 0 + $customer_id;
        $orderCustomer = $orderCustomers
            ->where('order_id', $order->id)
            ->where('customer_id', $customer_id)
            ->first();
        if (!$orderCustomer) {
            $rejection = 403;
            $status = 'No Record or additional traveller order';
            \Log::info('orderCustomer not found order_id'.$order->id.' customer_id'.$customer_id); 
        } else {
            \Log::info('orderCustomer found', $orderCustomer->toArray());
        }
        // flight exists?
        // $flight = $flights->find($flight_id);
        // if (!$flight) {
        //     $rejection = 402;
        //     $status = 'Invalid flight selection';
        // }
        // // flight Tour exists?
        // $flightTour = $flightTours
        //     ->where('flight_inventory_id', $flight->id)
        //     ->where('tour_id', $tour_id)
        //     ->where('flight_type', $flight_type)
        //     ->first();

        if ($rejection || !$customer_id || !$flightTour->id) {
            return [
                'status' => $rejection,
                'message' => $status
            ];
        }

        // customer_order_details
        $customer_order_details = new CustomerOrderDetails();
        $customer_order_detail = $customer_order_details
            ->where('orders_customer_id', $orderCustomer->id)
            ->where('order_id', $order->id)
            ->where('type', 'flight')
            ->where('inventory_tour_id', $flightTour->id)
            ->first();
        if (empty($customer_order_detail)) {
            $customer_order_detail = $customer_order_details;
            $customer_order_detail->status = 'new';
        } else {
            $customer_order_detail->status = 'update';

        }
        $customer_order_detail->orders_customer_id = $orderCustomer->id;
        $customer_order_detail->order_id = $order->id;
        $customer_order_detail->type = 'flight';
        $customer_order_detail->inventory_tour_id = $flightTour->id;
        $customer_order_detail->date_time = now();

        // these parameters have yet to be established
        // reference - the leads hash?
        // addon if there is a cost associated: where from?
        // cost for addon
        $customer_order_detail->reference = 'reference';
        $customer_order_detail->addon = false;
        $customer_order_detail->cost = 0;
        \Log::info('saving customer_order_detail record', $customer_order_detail->toArray());
        $customer_order_detail->save();
        // order_flights exists? :: LOGIC FAULT
        // order_flights must have a reference to the flight_inventory_tour (not the flight_inventory)
        // to find/update the order we need to know the order_customer_id, and the tour_id
        // then we can update the flight_inventory_tour id -> flight_inventory_id -> flight_id
        // $orderFlights = new OrdersFlight();
        // $orderFlight = $orderFlights->where('order_customer_id', $orderCustomer->id)
        //     ->where('flight_id', $flight->id)
        //     ->first();
        // if(!$orderFlight) {
        //     $orderFlight = new OrdersFlight();
        //     $orderFlight->flight_id = $flightTour->id;
        //     $orderFlight->order_customer_id = $orderCustomer->id;
        //     $orderFlight->save();
        //     $rejection = 200;
        //     $status = 'Created order flight';
        // } else {
        //     $orderFlight->flight_id = $flightTour->id;
        //     $orderFlight->save();
        //     $rejection = 201;
        //     $status = 'Updated order flight';
        // }
        \Log::info('booking flight details:', [$tour_id, $flightTour->id, $customer_id]);
        return [
            'status' => $rejection,
            'message' => $status
        ];
    }

    /** 
     * getCustomerByToken
     * 
     * @param $token
     * @return $customer or NULL if token no longer valid
     */
    public function getCustomerOrderByToken($token = null)
    {
        if (empty($token)) {
            return null;
        }

        $order = new Order();
        $orders = $order->where('token', $token)->get();
        if ($this->logging) {
            \Log::info($token . ' found '. count($orders). ' orders');
        }

        if (count($orders)) {
            $orderCount = count($orders);
            if ($orderCount > 1) {
                \Log::info('Multiple orders '.$orderCount.' for token '. $token);
            }

            if ($this->logging) \Log::info('orders are ', $orders->toArray());
            
            foreach ($orders as &$ord) {
                $ordersCustomers = new OrdersCustomer();
                $orderCustomer = $ordersCustomers->where('order_id', $ord->id)
                    ->join('customers', 'orders_customers.customer_id', 'customers.id')
                    ->get();
                if (count($orderCustomer)) {
                    $ord->customer = $orderCustomer[0];
                    $ord->customers = $orderCustomer;
                }
            }
            if ($this->logging) \Log::info('order data for customer retrieved ', $orders->toArray());

            return $orders;
        }
        return null;
    }
    /**
     * leadTraveller - save the leadTraveller data
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function leadTraveller(Request $request) 
    {
        if ($this->logging) {
            \Log::info('leadTraveller', $request->toArray());
        }

        $customer = $this->saveCustomerDetails($request, true);
        $orderCustomer = $this->saveOrderCustomer($customer, $request, true);

        return json_encode(['customer' => $customer, 'orderCustomer' => $orderCustomer]);
    }

    /**
     * additionalTraveller - save the additionalTraveller data
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function additionalTraveller(Request $request) 
    {
        if ($this->logging) {
            \Log::info('additionalTraveller', $request->toArray());
        }

        $customer = $this->saveCustomerDetails($request);
        $orderCustomer = $this->saveOrderCustomer($customer, $request, false);

        return json_encode(['customer' => $customer, 'orderCustomer' => $orderCustomer]);
    }

    /**
     * getTravellers for this order
     *
     * @param Request $request
     * @return JSON
     */
    public function getTravellers(Request $request) {
        if (empty($request->order_id)) {
            \Log::debug('ERROR: getTravellers requires an order_id');
            return null;
        }
        $customer = new Customer();
        $customers = $customer
            ->select('orders.id as order_id', 'orders_customers.id as order_customer_id', 'orders_customers.is_lead_booker', 'customers.id as customer_id', 'customers.first_name', 'customers.last_name')
            ->join('orders_customers', 'orders_customers.customer_id', 'customers.id')
            ->join('orders', 'orders.id', 'orders_customers.order_id')
            ->where('orders.id', $request->order_id)->get();
        
            return $customers->toJson();
    }
}
