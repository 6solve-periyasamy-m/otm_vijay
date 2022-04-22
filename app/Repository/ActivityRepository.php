<?php

namespace App\Repository;

use App\Models\Tour;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;

interface ActivityRepositoryInterface 
{
    public function __construct();
}

class ActivityRepository implements ActivityRepositoryInterface 
{
    private $debug = false;

    public function __construct()
    {
        $this->model = new Activity();
    }

    public function get(Tour $tour)
    {
        $activities = $this->model
            ->join('activity_inventories', 'activity_inventories.activity_id', 'activities.id')
            ->join('activity_inventory_tours', 'activity_inventory_tours.activity_inventory_id', 'activity_inventories.id')
            ->leftJoin('activity_inventory_tour_upgrades', 'activity_inventories.id', 'activity_inventory_tour_upgrades.upgrade_id')
            ->join('ticket_types', 'activity_inventories.ticket_type_id', 'ticket_types.id')
            ->join('addresses', 'addresses.id', 'activities.id')
            ->select('activity_inventories.notes as activity_notes',
            'activity_inventories.stock',
            'activity_inventory_tours.id as activity_inventory_tour_id',
            'activity_inventory_tours.tour_component_type', 
            'activities.*',
            'activity_inventories.starts_at', 'activity_inventories.ends_at',
            'activities.id as activity_id',
            'addresses.name as address',
            'addresses.region as address_region',
            'ticket_types.name as ticket_type_name')
            ->whereNull('activities.deleted_at')
            ->whereNull('activity_inventories.deleted_at')
            ->whereNull('activity_inventory_tours.deleted_at')
            ->where('activity_inventory_tours.tour_id', $tour->id)
            ->orderBy('activity_inventories.starts_at')
            ->orderBy('tour_component_type')
            //->where('activity_inventory_tours.tour_component_type', 'Included')
            ->get();

//dd($activities);
        return $activities;
    }

    public function getAll()
    {
        $activities = $this->model->get();

        return $activities;
    }
}
