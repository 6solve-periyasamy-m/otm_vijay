<?php

// Booking Controller: BOOKING FORM Updating API
// TODO: REFACTOR

namespace App\Http\Controllers\Api;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BookingTraveller;
use Illuminate\Support\Facades\Log;
use App\Http\Gateways\StripeGateway;
use App\Repository\BookingRepository;
use App\Repository\FlightsRepository;
use App\Repository\ActivityRepository;
use App\Repository\CustomerRepository;
use App\Http\Controllers\ApiController;
use App\Repository\AccommodationRepository;
use App\Repository\FlightBookingRepository;
use App\Repository\ActivityBookingRepository;
use App\Repository\BookingTravellerRepository;
use App\Repository\TransportBookingRepository;

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

        $bookingTravellerRepo = new BookingTravellerRepository();
        $traveller_id = $bookingTravellerRepo->create($booking->id, $customer_id);

        // Log::debug('Booking:Create', [$tour_id, $token]);
        return response()->json(["success" => true, 'booking' => $booking]);
    }

    public function gatherDetails($token)
    {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (empty($booking)) {
            return 'No booking';
        }
        $tour = $booking->tour;
        $customerRepo = new CustomerRepository();
        $travellerRepo = new BookingTravellerRepository();
        $flightsBookingRepo = new FlightBookingRepository();
        $accommodationRepo = new AccommodationRepository();

        $customer = $customerRepo->get($booking->customer_id);
        $travellers = $travellerRepo->getGroup($booking->id);
        foreach($travellers as &$traveller) {
            $traveller->customer = $customerRepo->get($traveller->customer_id);
        }
        $flightsOutbound = $flightsBookingRepo->getFlightBookings($booking->id, 'Outbound', 'Included');
        $flightsInbound = $flightsBookingRepo->getFlightBookings($booking->id, 'Inbound', 'Included');
        $accommodation = $accommodationRepo->getAccommodationBooking($booking, $travellerRepo->getIds($booking->id));

        $activityBookingRepo = new ActivityBookingRepository();
        $transportBookingRepo = new TransportBookingRepository();
        $activities = $activityBookingRepo->getBookingsForTour($booking);
        $transports = $transportBookingRepo->getBookingsForTour($tour, $booking);

        return response()->json([
            'success' => true, 
            'booking' => [
                'customer' => $customer,
                'travellers' => $travellers,
                'flights' => ['outbound' => $flightsOutbound, 'inbound' => $flightsInbound],
                'accommodations' => $accommodation,
                'activities' => $activities,
                'transports' => $transports
            ]
        ]);
    }

    /**
     * calculateDeposit
     * determine deposit and display payment form
     */
    public function calculateDeposit(Request $request)
    {
        // API call (not input), validate it anyway
        if (empty($request->tour) || empty($request->tour['id']) || strlen($request->token) < 6) {
          return response(['success' => false, 'error' => 'invalid data in deposit request']);
        }

        $token = $request->token;
        $booking = Booking::where('token', $token)->first();
        $customer = Customer::find($booking->customer_id);

        $tour = Tour::find($request->tour['id']);
        if (!$tour) {
          return response(['success' => false, 'error' => 'Non-existant tour']);
        }
        $data = $this->getCustomerAndBooking($token);
        $deposit = $tour->deposit * $data['travellers'];
Log::debug('deposit', [$deposit]);
        //return view('pages.booking.deposit.payment', ['errors' => [], 'booking' => $booking, 'customer' => $customer, 'deposit' => $deposit]);
        return response(['success' => true, 'deposit' => $deposit]);
    }

    private function getCustomerAndBooking($token)
    {
        $bookingRepository = new BookingRepository();
        $booking = $bookingRepository->findBookingByToken($token);
Log::debug('booking...', [$booking, $token]);
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->get($booking->customer_id);

        $travellers = BookingTraveller::where('booking_id', $booking->id)->count();

        return ['customer' => $customer, 'travellers' => $travellers, 'booking' => $booking];
    }
}
