<?php

namespace App\Http\Controllers\Api;

 use App\Http\Controllers\ApiController;
use App\Models\Tour\Tour;
use App\Repository\ActivityBookingRepository;
use App\Repository\ActivityRepository;
use App\Repository\BookingRepository;

class ActivitiesController extends ApiController
{
    /**
     * getActivites: gets all activities
     *
     * @return void
     */
    public function getActivities()
    {
        $activitiesRepository = new ActivityRepository();
        $activities = $activitiesRepository->getAll();

        return response()->json(['success' => true, 'activities' => $activities]);
    }

    /**
     * getActivitiesForTour
     *
     * @param Tour $tour 
     * @return void
     */
    public function getActivitiesInventoryForTour(Tour $tour)
    {
        $activitiesRepository = new ActivityRepository();
        $activities = $activitiesRepository->get($tour);

        return response()->json(['success' => true, 'activities' => $activities]);
    }

    /**
     * getActivitiesBooking
     *
     * @param [type] $token
     * @param Tour $tour
     * @return void
     */
    public function getActivitiesBooking($token, Tour $tour)
    {
        $bookings = new BookingRepository();
        $booking = $bookings->findBookingByToken($token);

        $activityBookingRepository = new ActivityBookingRepository();
        $bookings = $activityBookingRepository->getBookingsForTour($booking);

        return response()->json(['success' => true, 'bookings' => $bookings]);
    }
}
