<?php

namespace App\Repository;

use App\Models\Flight;

interface FlightsRepositoryInterface {
    public function getFlights($tour_id);
}

class FlightsRepository implements FlightsRepositoryInterface
{
    protected $model;

    public function __construct(Flight $model)
    {
        $this->model = $model;
    }
    public function getFlights($tour_id) 
    {
        $flights = $this->model->join('airlines', 'airline_id', 'id')
            ->join('flight_inventory_tour', 'tour_id', $tour_id)
            ->join('flight_inventories', 'flight_inventory_tour.flight_inventory_id', 'id')
            ->where('flights.tour_id', $tour_id)
            ->orderBy('airlines.name')
            ->get();

        return $flights;
    }
}
