<?php
/**
 * BookingController:: API
 */
namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\BookingTraveller;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;
use App\Repository\AccommodationRepository;
use App\Repository\FlightBookingRepository;
use App\Repository\ActivityBookingRepository;
use App\Repository\BookingTravellerRepository;
use App\Repository\TransportBookingRepository;

class BookingController extends ApiController
{
    protected $logging = 'customer';

    /**
     * get retrieve a booking 
     *
     * @param $token
     * @return void
     */
    public function get($token)
    {
        $booking = BookingRepository::findBooking($token);

        if (isset($booking)) {
            return response()->json(['success' => true, 'booking' => $booking, 'tour' => $booking->tour]);
        }
        return response()->json(['success' => false]);
    }

  /**
   * collect booking references for this customer
   * NB: Customer must be logged in
   * @param $customer_id
   * @return JSON booking data
   */
   public function collect($customer_id)
   {
      if (!Auth::user()) {
        return response()->json(['success' => false, 'message' => 'Not allowed']);
        throw new Exception('Can not get this data unless logged in');
      }
      // todo : move to repo
      $booking = new Booking();
      $bookings = $booking->select('tours.name as tour_name', 'bookings.token', 'bookings.status')
        ->join('tours', 'tours.id', 'bookings.tour_id')
        ->where('customer_id', $customer_id)
        ->orderBy('tour_id', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

      return response()->json(['success' => true, 'data' => $bookings]);
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
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $token = $request->token;

        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->create($customer_id, $tour_id, $token);
        $booking->customer_id = (new BookingTravellerRepository)->create($booking->id, $customer_id);
        
        return response()->json(["success" => true, 'booking' => $booking]);
    }

    /**
     * gatherDetails: GET json data for a token for the booking summary
     *
     * @param STRING $token
     * @return JSON response
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
     * @return JSON response
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
