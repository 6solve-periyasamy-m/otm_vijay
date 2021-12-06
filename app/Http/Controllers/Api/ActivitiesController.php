<?php

namespace App\Http\Controllers\Api;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repository\BookingRepository;
use App\Http\Controllers\ApiController;
use App\Repository\ActivityBookingRepository;

class ActivitiesController extends ApiController
{
    /**
     * getActivites: gets all activities
     *
     * @return void
     */
    public function getActivities($tour)
    {
        $activitiesRepository = new ActivityBookingRepository();
        $activities = $activitiesRepository->get();

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
        $activitiesRepository = new ActivityBookingRepository();
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

        $activitiesRepository = new ActivityBookingRepository();
        $bookings = $activitiesRepository->getBookingsForTour($tour, $booking);

        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    /**
     * updateActivities
     * creates/updates booking activities
     *
     * @param Request $request
     * @return void
     */
    public function updateActivities(Request $request)
    {

    }
}
