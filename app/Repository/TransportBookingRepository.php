<?php

namespace App\Repository;

use App\Models\Tour;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Transport;

interface TransportBookingRepositoryInterface {
    public function __construct();
    public function getTransports(Tour $tour = null, String $tour_component_type = 'Included');
    public function getBookings(Tour $tour = null, String $tour_component_type = 'Included');
    public function getBookingsForTour(Tour $tour, Booking $booking);
}

class TransportBookingRepository implements TransportBookingRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new Transport();
    }

    private function addressFormat($address)
    {
        return $address->name . ', ' . $address->address_line_1 . ', ' . $address->address_line_2 . ', ' . $address->town . ' ' . $address->postcode;
    }

    /**
     * getTransports: returns a collection of (Included|other) transports available (for a tour or any tour)
     *
     * @param Tour|null $tour
     * @param String|'Included' $tour_component_type 
     * @return void
     */
    public function getTransports(Tour $tour = null, String $tour_component_type = 'Included')
    {
        $transport = new Transport();
        $transports = $transport
            ->join('transport_inventories', 'transport_inventories.transport_id', '=', 'transports.id')
            ->join('transport_inventory_tours', 'transport_inventory_tours.transport_inventory_id', '=', 'transport_inventories.id')
            ->join('transport_types', 'transports.transport_type_id', '=', 'transport_types.id')
            ->select(
                'transports.departure_address_id',
                'transports.arrival_address_id',
                'transports.is_domestic', 'transports.name',
                'transports.description', 'transports.notes',
                'transport_inventories.sales_price',
                'transport_inventories.notes as transport_notes',
                'transport_inventories.departs_at as departs_at',
                'transport_inventories.arrives_at as arrives_at',
                'transport_inventory_tours.tour_component_type',
                'transport_types.name as transport_type_name')
            ->whereNull('transports.deleted_at')
            ->whereNull('transport_inventories.deleted_at')
            ->whereNull('transport_inventory_tours.deleted_at')
            ->where('transport_inventory_tours.tour_component_type', $tour_component_type);
        if ($tour) {
            $transports = $transports->where('transport_inventory_tours.tour_id', $tour->id);
        }

        $transports = $transports->get();
        foreach($transports as &$transport) {
            $departure = Address::find($transport->departure_address_id);
            $arrival = Address::find($transport->arrival_address_id);
            $transport->departure_address = $this->addressFormat($departure);
            $transport->arrival_address = $this->addressFormat($arrival);                
        }

        return $transports;
    }

    /**
     * getBookings: booking does not allow selection of transports, so all transports are returned
     *
     * @param Tour|null $tour
     * @param string $tour_component_type
     * @return void
     */
    public function getBookings(Tour $tour = null, String $tour_component_type = 'Included')
    {
        return $this->getTransports($tour, $tour_component_type);
    }

    /**
     * accessor for a tour with bookings included
     *
     * @param Tour $tour
     * @param Booking $booking
     * @return void
     */
    public function getBookingsForTour(Tour $tour, Booking $booking)
    {
        return $this->getTransports($tour, 'Included');

        // when bookings of transport are active...test that this retrieves them
        // return $this->getBookings($tour);
    }
}
