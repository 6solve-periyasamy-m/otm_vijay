<?php

namespace App\Repository;

use App\Models\Action;
use App\Models\Booking;

use App\Models\BookingFlight;
use App\Models\BookingFlights;
use Illuminate\Support\Facades\Log;

interface FlightBookingRepositoryInterface {
    public function __construct();
    public function getFlightBookings($booking_id);
}

class FlightBookingRepository implements FlightBookingRepositoryInterface
{
    protected $model;
    private $logging = 0;

    public function __construct()
    {
        $this->model = new BookingFlights();
    }
    public function getFlightBookings($booking_id, $type = 'Both', $tour_component_type = 'Included')
    {
        // $booking = new Booking();
        $flightBooking = $this->model
            ->select('bookings.id as booking_id', 
                'bookings.customer_id',
                'booking_flights.id as booking_flight_id',
                'flights.id as flight_id',
                'flight_inventory_tours.id as flight_inventory_tour_id',
                'flight_inventories.travel_class_id',
                'travel_classes.name as travel_class',
                'flight_inventory_tours.tour_component_type', 
                'flight_inventory_tours.flight_type', 
                'flight_inventory_tours.tour_sales_price') 
            ->join('bookings', 'booking_flights.booking_id', 'bookings.id')
            ->join('flight_inventory_tours', 'flight_inventory_tours.id', 'booking_flights.flight_inventory_tour_id')
            ->join('flight_inventories', 'flight_inventory_tours.flight_inventory_id', 'flight_inventories.id')
            ->join('flights', 'flights.id', 'flight_inventories.flight_id')
            ->join('travel_classes', 'travel_classes.id', 'flight_inventories.travel_class_id')
            ->leftJoin('airlines', 'airlines.id', 'flights.airline_id')
            ->where('bookings.id', $booking_id)
            ->where('flight_inventory_tours.tour_component_type', $tour_component_type)
            ->whereNull('bookings.deleted_at');
Log::info($flightBooking->toSql());
        if ($type !== 'Both') {
            $flightBooking = $flightBooking->where('flight_inventory_tours.flight_type', $type);
        }
        $flightBookings = $flightBooking->get();
        if ($this->logging > 5) {
            Log::info('Fight Bookings :', [$flightBookings]);
        }
        if ($this->logging > 3) {
            $sql = $flightBooking->toSql();
            Log::info('query', [$sql]);
        }
        if ($this->logging > 0) {
            Log::info('Fight Bookings Found:' . $flightBookings->count());
        }
        return $flightBookings;
    }
}
