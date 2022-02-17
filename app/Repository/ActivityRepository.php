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
            ->join('ticket_types', 'activity_inventories.ticket_type_id', 'ticket_types.id')
            ->select('activity_inventories.notes as activity_notes', 
            'activity_inventory_tours.id as activity_inventory_tour_id', 
            'activities.*', 
            'activity_inventories.starts_at', 'activity_inventories.ends_at',
            'ticket_types.name as ticket_type_name')
            ->whereNull('activities.deleted_at')
            ->whereNull('activity_inventories.deleted_at')
            ->whereNull('activity_inventory_tours.deleted_at')
            ->where('activity_inventory_tours.tour_id', $tour->id)
            ->where('acivity_inventory_tours.tour_component_type', 'Included')
            ->get();

        return $activities;
    }

    public function getAll()
    {
        $activities = $this->model->get();

        return $activities;
    }
}
