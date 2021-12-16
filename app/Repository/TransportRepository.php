<?php

namespace App\Repository;

use App\Models\Tour;
use App\Models\Transport;
use Illuminate\Support\Facades\Log;

interface TransportRepositoryInterface 
{
    public function __construct();
}

class TransportRepository implements TransportRepositoryInterface 
{
    public function __construct()
    {
        $this->model = new Transport();
    }

    public function get(Tour $tour)
    {
        $transports = $this->model
            ->join('transport_inventories', 'transport_inventories.transport_id', 'transports.id')
            ->join('transport_inventory_tours', 'transport_inventory_tours.transport_inventory_id', 'transport_inventories.id')
            ->join('travel_classes', 'transport_inventories.travel_class_id', 'travel_classes.id')
            ->select('transport_inventories.notes as transport_notes', 
            'transport_inventory_tours.id as transport_inventory_tour_id', 
            'transports.*', 
            'transport_inventories.departs_at', 'transport_inventories.arrives_at',
            'travel_classes.name as travel_class_name')
            ->whereNull('transports.deleted_at')
            ->whereNull('transport_inventories.deleted_at')
            ->whereNull('transport_inventory_tours.deleted_at')
            ->where('transport_inventory_tours.tour_id', $tour->id)
            ->get();

        return $transports;
    }

    public function getAll()
    {
        $transports = $this->model->get();

        return $transports;
    }
}
