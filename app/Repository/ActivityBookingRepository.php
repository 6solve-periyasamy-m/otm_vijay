<?php

namespace App\Repository;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\BookingActivities;
use Illuminate\Support\Facades\Log;

interface ActivityBookingRepositoryInterface {
    public function __construct();
    public function getBookings(Tour $tour);
    public function getActivities(Tour $tour);
    public function getBookingsForTour(Booking $booking);
    public function create($booking);
}

class ActivityBookingRepository implements ActivityBookingRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new BookingActivities();
    }

    public function getBookings(Tour $tour = null)
    {
        Log::debug('get', [$tour]);
        if (isset($tour)) {
            $activities = $this->model
                ->select(
                    'activities.name as activities_name',
                    'activities.description',
                    'activity_inventories.notes as activity_notes', 
                    'activity_inventory_tours.id as activity_inventory_tour_id', 
                    'activity_inventories.starts_at', 'activity_inventories.ends_at',
                    // 'activity_inventories.sale_price', 'activity_inventories.purchase_price',
                    // 'acitity_inventory_tours.tour_sale_price',
                    'ticket_types.name as ticket_type_name')
                ->join('activity_inventory_tours', 'booking_activities.activity_inventory_tour_id', 'activity_inventory_tours.id')
                ->join('activity_inventories', 'activity_inventory_tours.activity_inventory_id', 'activity_inventories.id')
                ->join('activities', 'activity_inventories.activity_id', 'activities.id')
                ->join('ticket_types', 'activity_inventories.ticket_type_id', 'ticket_types.id')
                ->where('tour_id', $tour->id)
                ->whereNull('activities.deleted_at')
                ->whereNull('activity_inventories.deleted_at')
                ->whereNull('activity_inventory_tours.deleted_at')
                ->get();
            Log::debug('activities query', [$activities]);
        } else {
            $activities = $this->model->get();
        }

        return $activities;
    }

    public function getActivities(Tour $tour = null)
    {
        Log::debug('get', [$tour]);
        if (isset($tour)) {
            $activity = new Activity();
            $activitiesQuery = $activity
                // ->join('activities', 'activity_inventories.activity_id', 'activities.id')
                ->join('activity_inventories', 'activities.id', 'activity_inventories.activity_id')
                ->join('activity_inventory_tours', 'activity_inventories.id', 'activity_inventory_tours.activity_inventory_id')
                ->join('ticket_types', 'activity_inventories.ticket_type_id', 'ticket_types.id')
                ->select(
                    'activities.name as activities_name',
                    'activities.description',
                    'activity_inventories.notes as activity_notes', 
                    'activity_inventory_tours.id as activity_inventory_tour_id', 
                    'activity_inventories.starts_at', 'activity_inventories.ends_at',
                    'activity_inventories.sales_price', 'activity_inventories.purchase_price',
                    'activity_inventory_tours.tour_sales_price',
                    'ticket_types.name as ticket_type_name')
                ->where('tour_id', $tour->id)
                ->whereNull('activities.deleted_at')
                ->whereNull('activity_inventories.deleted_at')
                ->whereNull('activity_inventory_tours.deleted_at');
            $activities = $activitiesQuery->get();

            Log::debug('<<<<<<<>>>>>>> activities query', [$activitiesQuery->toSql(), $activities]);
        } else {
            $activities = $this->model->get();
        }

        return $activities;
    }


    /**
     * getBookingsForTour
     * When bookings for activities are stored
     *
     * @param Booking $booking
     * @return void
     */
    public function getBookingsForTour(Booking $booking)
    {
        // return $this->get($booking->tour);

        // get the preset activities for this tour (fixed)
        $activitiesBooking = $this->getActivities($booking->tour);

        // when activity bookings are recorded, test this query
        // $activitiesBooking = $this->getBookings($booking->tour);

        return $activitiesBooking;
    }

    public function create($booking)
    {

    }
}