<?php

// Booking Controller: BOOKING FORM Updating API
// TODO: REFACTOR

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repository\BookingRepository;
use App\Repository\FlightsRepository;
use App\Repository\CustomerRepository;
use App\Http\Controllers\ApiController;
use App\Repository\AccommodationRepository;
use App\Repository\ActivityBookingRepository;
use App\Repository\TransportBookingRepository;
use App\Repository\AdditionalTravellerRepository;

class BookingController extends ApiController
{
    protected $logging = 'customer';

    /**
     * get
     *
     * @param $token
     * @return void
     */
    public function get($token)
    {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (isset($booking)) {
            // Log::debug('===>>> booking->customer', [$booking, $booking->customer]);
            return response()->json(['success' => true, 'booking' => $booking, 'tour' => $booking->tour]);
        }
        return response()->json(['success' => false]);
    }

    /**
     * create
     * POST function to create a booking in the repo
     * @param Request $request with parameters:
     * @param string $customer_id
     * @param string $tour_id
     * @param string $token
     * @return void
     */
    public function create(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'tour_id' => 'required',
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $token = $request->token;

        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->create($customer_id, $tour_id, $token);
Log::debug('Booking:Create', [$tour_id, $token]);
        return response()->json(["success" => true, 'booking' => $booking]);
    }

    public function gatherDetails($token)
    {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);

        $customerRepo = new CustomerRepository();
        $travellerRepo = new AdditionalTravellerRepository();
        $flightsRepo = new FlightsRepository();
        $accommodationRepo = new AccommodationRepository();
        $activityRepo = new ActivityBookingRepository();
        $transportRepo = new TransportBookingRepository();
    }
}
