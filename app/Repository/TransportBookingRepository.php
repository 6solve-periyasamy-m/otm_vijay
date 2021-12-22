<?php

namespace App\Repository;

use Exception;
use App\Models\Tour;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Transport;
use App\Models\TransportType;
use Illuminate\Support\Facades\Log;

interface TransportBookingRepositoryInterface {
    public function __construct();
    public function getBookings(Tour $tour);
    public function getBookingsForTour(Tour $tour, Booking $booking);
    public function create($booking);
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
    public function getTransports(Tour $tour = null)
    {
        if (isset($tour)) {
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
                ->where('transport_inventory_tours.tour_id', $tour->id)
                ->whereNull('transports.deleted_at')
                ->whereNull('transport_inventories.deleted_at')
                ->whereNull('transport_inventory_tours.deleted_at')
                ->where('transport_inventory_tours.tour_component_type', 'Included');
        } else {
            throw new Exception('no tour in Transport query');
        }

        $transports = $transports->get();

        foreach($transports as &$transport) {
            // $transportType = TransportType::find($transport->transport_type_id);
            // $transport->transport_type = $transportType->name;
            // Log::debug('type:', [$transport->transport_type_id, $transport->transport_type_name, $transport->transport_type, $transport->sales_price]);
            $departure = Address::find($transport->departure_address_id);
            $arrival = Address::find($transport->arrival_address_id);
            $transport->departure_address = $this->addressFormat($departure);
            $transport->arrival_address = $this->addressFormat($arrival);                
        }
        // Log::debug('transports!', [$transports]);

        return $transports;
    }

    public function getBookings(Tour $tour = null)
    {
        if (isset($tour)) {
            $transports = $this->model
                ->join('transport_inventory_tours', 'booking_transports.transport_inventory_id', 'transport_inventory_tours.id')
                ->join('transport_inventories', 'transport_inventory_tours.transport_inventory_id', 'transport_inventories.id')
                ->join('transports', 'transport_inventories.transport_id', 'transports.id')
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
                ->where('transport_inventory_tours.tour_id', $tour->id)
                ->whereNull('transports.deleted_at')
                ->whereNull('transport_inventories.deleted_at')
                ->whereNull('transport_inventory_tours.deleted_at')
                ->where('transport_inventory_tours.tour_component_type', 'Included');
        } else {
            throw new Exception('no tour in Transport query');
        }
        // $query = $transports->toSql();
        // Log::debug('Transports query:', [$query]);

        $transports = $transports->getBookings();
        // Log::debug('transports result', [$transports]);

        foreach($transports as &$transport) {
            // $transportType = TransportType::find($transport->transport_type_id);
            // $transport->transport_type = $transportType->name;
            // Log::debug('type:', [$transport->transport_type_id, $transport->transport_type_name, $transport->transport_type, $transport->sales_price]);
            $departure = Address::find($transport->departure_address_id);
            $arrival = Address::find($transport->arrival_address_id);
            $transport->departure_address = $this->addressFormat($departure);
            $transport->arrival_address = $this->addressFormat($arrival);                
        }
        // Log::debug('transports!', [$transports]);

        return $transports;
    }

    public function getBookingsForTour(Tour $tour, Booking $booking)
    {
        return $this->getTransports($tour);

        // when bookings of transport are active...test that this retrieves them
        // return $this->getBookings($tour);
    }

    public function create($booking)
    {

    }
}