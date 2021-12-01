<?php

namespace App\Repository;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;

interface ActivityBookingRepositoryInterface {
    public function __construct();
    public function get(Tour $tour);
    public function getBookingsForTour(Tour $tour, Booking $booking);
    public function create($booking);
}

class ActivityBookingRepository implements ActivityBookingRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new Activity();
    }

    public function get(Tour $tour = null)
    {
        if (isset($tour)) {
            $activities = $this->model
                ->select('activity_inventories.sales_price', 'activity_inventories.notes as activity_notes', 'activity_inventory_tours.*', 'activities.*', 'ticket_types.name as ticket_type_name')
                ->join('activity_inventories', 'activity_inventories.activity_id', 'activities.id')
                ->join('activity_inventory_tours', 'activity_inventory_tours.activity_inventory_id', 'activity_inventories.activity_id')
                ->join('ticket_types', 'activity_inventories.ticket_type_id', 'ticket_types.id')
                ->where('tour_id', $tour->id)
                ->distinct()
                // ->whereNull('deleted_at')
                ->get();
        } else {
            $activities = $this->model->get();
        }

        Log::debug('activities!', [$activities]);

        return $activities;
    }

    public function getBookingsForTour(Tour $tour, Booking $booking)
    {
        Log::debug('getBookingsForTour', [$tour, $booking]);

        return 'no data yet';
    }

    public function create($booking)
    {

    }
}