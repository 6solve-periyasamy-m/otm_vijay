<?php
/**
 * BookingController:: API
 */
namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;

use App\Models\BookingTraveller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;
use App\Http\Controllers\ApiController;
use App\Repository\AccommodationRepository;
use App\Repository\FlightBookingRepository;
use App\Repository\ActivityBookingRepository;
use App\Repository\BookingTravellerRepository;
use App\Repository\TransportBookingRepository;

class BookingController extends ApiController
{
    protected $logging = '';

    /**
     * get retrieve a booking 
     *
     * @param $token
     * @return void
     */
    public function get($token)
    {
        $booking = BookingRepository::findBooking($token);
        if (empty($booking)) {
            return response()->json(['success' => false]);
        }

        $customer = Customer::find($booking->customer_id);
        $customer->home_address = Address::find($customer->home_address_id);
        $customer->billing_address = Address::find($customer->billing_address_id);
        // Log::debug('getBooking: customer', [$customer, $booking]);
        if (isset($booking)) {
            return response()->json(['success' => true, 'booking' => $booking, 'customer' => $customer, 'tour' => $booking->tour]);
        }
        return response()->json(['success' => false]);
    }

  /**
   * collect booking references for this customer
   * NB: Customer must be logged in
   * @param $customer_id
   * @return JSON booking data
   */
   public function collect($token)
   {
      //$booking = Booking::select('customer_id')->where('token', $token)->first();
      $booking = BookingRepository::findBooking($token);
      if (!$booking) {
        return response()->json(['success' => false, 'message' => 'No bookings for token '.$token]);
      }

      $bookings = Booking::select('bookings.id as booking_id', 'tours.name as tour_name', 'bookings.token', 'bookings.name', 'bookings.status')
        ->join('tours', 'tours.id', 'bookings.tour_id')
        ->where('customer_id', $booking->customer_id)
        ->orderBy('bookings.tour_id', 'desc')
        ->orderBy('bookings.created_at', 'desc')
        ->get();
        Log::debug('Booking Collected: ', [$bookings]);

        return response()->json(['success' => true, 'bookings' => $bookings]);
   }

    /**
     * create
     * POST function to create a booking in the repo
     * @param Request $request with parameters:
     * @param string $customer_id
     * @param string $tour_id
     * @param string $token
     * @return JSON response
     */
    public function create(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'tour_id' => 'required',
            'token' => 'required',
            'fullname' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $token = $request->token;
        $fullname = $request->fullname;
        // Log::debug('booking create', [$customer_id, $tour_id, $token, $name]);

        // check if a booking is active
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (!$booking) {
            $booking = $bookingRepo->create($customer_id, $tour_id, $token, $fullname);
            $booking->customer_id = (new BookingTravellerRepository)->create($booking->id, $customer_id);
            $booking->token = $token;
            $booking->name = $fullname;
        } else {
            Log::warning('BookingCreate: booking already exists: name updated', [$fullname]);
            $booking->save();
        }
        return response()->json(["success" => true, 'booking' => $booking]);
    }

    /**
     * update booking name
     *
     * @param Request $request
     * @return JSON Booking
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'token' => 'required'
        ]);
        $booking = Booking::where('token', $request->token)->first();
        $booking->name = $request->name;
        $booking->save();

        return response()->json(['success' => true, 'booking' => $booking]);
    }

    /**
     * gatherDetails: gather all details related to the booking token
     * 
     * @param STRING $token
     * @return JSON booking containing all comoponents
     */
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
        $activityBookingRepo = new ActivityBookingRepository();
        $transportBookingRepo = new TransportBookingRepository();

        $customer = $customerRepo->get($booking->customer_id);
        $travellers = $travellerRepo->getGroup($booking->id);
        foreach($travellers as &$traveller) {
            $traveller->customer = $customerRepo->get($traveller->customer_id);
        }
        $flightsOutbound = $flightsBookingRepo->getFlightBookings($booking->id, 'Outbound', 'Included');
        $flightsInbound = $flightsBookingRepo->getFlightBookings($booking->id, 'Inbound', 'Included');
        $accommodation = $accommodationRepo->getAccommodationBooking($booking, $travellerRepo->getIds($booking->id));
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
     * @Param Request OBJECT 
     *    $tour INT the ID of the tour being booked
     *    $token STRING Booking unique token (browser cookie) for validation
     * @return JSON deposit
     */
    public function calculateDeposit(Request $request)
    {
        // API call (not input), validate it anyway
        if (empty($request->tour) || empty($request->tour['id']) || strlen($request->token) < 6) {
          return response(['success' => false, 'error' => 'invalid data in deposit request']);
        }

        $token = $request->token;
        $booking = Booking::where('token', $token)->first();

        $tour = Tour::find($request->tour['id']);
        if (!$tour) {
            return response()->json(['success' => false, 'error' => 'Non-existant tour']);
        }
        if ($booking->tour_id !== $tour->id) {
            return response()->json(['success' => false, 'error' => 'Tour and Booking do not match']);
        }

        $data = $this->getCustomerAndBooking($token);
        $deposit = $tour->deposit * $data['travellers'];
        if ($deposit) {
                return response()->json(['success' => true, 'deposit' => $deposit]);
        } else {
                Log::debug('Deposit is ' . $deposit);
                throw new Exception('Deposit must be a positive value!');
        }
    }

    /**
     * getCustomerAndBooking
     *
     * @param STRING $token
     * @return ARRAY Booking, Customer and number of travellers in party
     */
    private function getCustomerAndBooking($token)
    {
        $booking = BookingRepository::findBooking($token);
        $customer = CustomerRepository::lookup($booking->customer_id);
        $travellers = BookingTraveller::where('booking_id', $booking->id)->count();

        return ['customer' => $customer, 'travellers' => $travellers, 'booking' => $booking];
    }
}
