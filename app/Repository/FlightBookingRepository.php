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
    public function __construct()
    {
        $this->model = new BookingFlights();
    }
    public function getFlightBookings($booking_id, $type = 'Both')
    {
        $booking = new Booking();
        $flightBooking = $booking
            // NB: careful: overly restrictive select and the joins may not work
            //    ->select('booking_flights.*','flights.*', 'flight_inventory_tours.*')
            ->join('booking_flights', 'booking_flights.booking_id', 'bookings.id')
            ->join('flight_inventories', 'flight_inventories.id', 'booking_flights.flight_inventory_id')
            ->join('flights', 'flights.id', 'flight_inventories.flight_id')
            ->join('flight_inventory_tours', 'flight_inventory_tours.flight_inventory_id', 'flight_inventories.id')
            ->leftJoin('airlines', 'airlines.id', 'flights.airline_id')
            ->where('bookings.id', $booking_id)
            ->whereNull('bookings.deleted_at');
        if ($type !== 'Both') {
            $flightBooking = $flightBooking->whereIn('flight_inventory_tours.flight_type', $type);
        }
        $sql = $flightBooking->toSql();
        Log::info('query', [$sql]);
        $flightBookings = $flightBooking->get();
        Log::info('query', [$flightBookings]);

        return $flightBookings;
    }
}
