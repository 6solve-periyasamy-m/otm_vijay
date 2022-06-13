<?php /** @noinspection DuplicatedCode */

namespace App\Http\Controllers\Customer;

use App\Exceptions\NotOnTourException;
use App\Exceptions\RoomingFailedException;
use App\Http\Controllers\Controller;
use App\Http\Gateways\StripeGateway;
use App\Http\Requests\Booking\BookingCustomerRequest;
use App\Models\Accommodation\RoomType;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingGroup;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\Model\Booking\BookingRepository;
use App\Repository\Model\Booking\BookingTravellerRepository;
use App\Repository\RoomingRepository;
use Illuminate\Http\Request;
use Session;
use URL;

class CustomerBookingController extends Controller
{
    private function getTour(string $bookingUrl, int $size = 1): ?Tour
    {
        $tour = Tour::where('booking_form_url', $bookingUrl)->where('is_active', true)->first();
        if ($tour->stock_control_active &&
            $tour->stock - $tour->getUsedStock() < $size) {
            return null;
        }
        return $tour;
    }

    private function getBooking(?string $token): ?Booking
    {
        if (!isset($token)) return null;
        return Booking::where('token', $token)->first();
    }

    public function index(string $bookingUrl, string $token = null)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour)) abort(404);
        $booking = $this->getBooking($token);
        $customer = isset($booking) ? $booking->leadTraveller : CustomerAuthenticationRepository::getCustomer();
        return view('pages.customer.booking.customers', [
            'tour' => $tour,
            'customer' => $customer,
            'token' => $token,
            'leadTraveller' => $customer,
            'additionalTravellers' => $booking?->travellers()->whereNot('id', $booking->leadTraveller?->id)->get(),
            'flights' => $booking?->repository->getAvailableFlights() ?? BookingRepository::make($tour)->repository->getAvailableFlights(),
            'rooms' => RoomingRepository::getAvailableRoomTypes($tour),
            'available' => $tour->stock - $tour->getUsedStock(),
        ]);
    }

    public function storeCustomers(BookingCustomerRequest $request, string $bookingUrl, string $token = null)
    {

        $tour = $this->getTour($bookingUrl, 1 + sizeof($request->input('additional') ?? []));

        if (!isset($tour)) abort(404);

        if (!CustomerAuthenticationRepository::verifyForBooking($request->lead_email_address)) {
            Session::put('url.intended', URL::full());
            return redirect()->route('customer.login');
        }

        $bookedEmails = [strtolower(trim($request->lead_email_address)),];
        foreach ($request->additional ?? [] as $additional) {
            if (in_array(strtolower(trim($additional['email_address'])), $bookedEmails)) {
                return back()->withErrors([
                    'msg' => 'You have used the email ' . $additional['email_address'] . ' for multiple customers. Please correct this.',
                ]);
            }
            $bookedEmails[] = strtolower(trim($additional['email_address']));
        }

        $booking = BookingRepository::create($tour, BookingTravellerRepository::make($request->getLeadTravellerDetails()));

        $leadGroup = BookingGroup::create(['name' => "Room $request->lead_group", 'booking_id' => $booking->id]);

        $leadRoomType = RoomType::find($request->lead_room_type);

        try { $leadGroup->repository->addTemplatesOfTypeToGroup($tour, $leadRoomType); }
        catch (RoomingFailedException) { /* Exception only thrown when using strict typing */ }

        $leadGroup->repository->addTravellerToGroup($booking->leadTraveller);

        $grouping = [$request->lead_group => ['group' => $leadGroup, 'roomType' => $leadRoomType,]];

        foreach ($request->additional ?? [] as $additional) {

            $traveller = BookingTravellerRepository::create($booking, $additional);

            $roomType = $traveller->roomType;
            $groupNumber = $traveller->group_id;

            do {
                if (key_exists($groupNumber, $grouping) &&
                    ($grouping[$groupNumber]['roomType']->id !== $roomType->id ||
                        $grouping[$groupNumber]['group']->travellers()->count() + 1 > $grouping[$groupNumber]['roomType']->maximum_occupancy)) {
                    $groupNumber++;
                    continue;
                }
                break;
            } while (true);

            $traveller->group_id = $groupNumber;
            $traveller->save();

            if (key_exists($groupNumber, $grouping)) {
                $grouping[$groupNumber]['group']->repository->addTravellerToGroup($traveller);
            } else {
                $group = BookingGroup::create(['name' => "Room $groupNumber", 'booking_id' => $booking->id]);
                $group->repository->addTravellerToGroup($traveller);
                $grouping[$groupNumber] = ['group' => $group, 'roomType' => $roomType,];
            }
        }

        try {
            $booking->repository->selectFlights($request->inbound, $request->outbound);
        } catch (NotOnTourException) {
            return back()->withErrors(['msg' => 'One of those flights is not included on this tour',]);
        }
        $booking->repository->addIncludedToAll();

        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl, 'token' => $booking->token,]);
    }

    public function components(string $bookingUrl, string $token)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour)) abort(404);
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() <= $booking->travellers()->count()) abort(404, 'That tour is out of stock');
        return view('pages.customer.booking.summary', ['booking' => $booking, 'tour' => $tour,]);
    }

    public function purchaseAddon(string $bookingUrl, string $token, string $id, string $type)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour)) abort(404);
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        $componentRepo = InventoryTourRepository::getComponent($type, $id);
        if (!isset($componentRepo)) abort(404);
        $booking->repository->addComponentToAll($componentRepo);
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl, 'token' => $token,]);
    }

    public function removeAddon(string $bookingUrl, string $token, string $id, string $type)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) {
            abort(404);
        }
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) {
            abort(404);
        }
        $componentRepo = InventoryTourRepository::getComponent($type, $id);
        if (!isset($componentRepo)) abort(404);
        $booking->repository->removeComponentFromAll($componentRepo);
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl, 'token' => $token,]);
    }

    public function payDeposit(Request $request, string $bookingUrl, string $token)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() <= 0) abort(404, 'That tour is out of stock');
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        $dueToday = $booking->repository->getDueTodayAmount();
        $min = max($dueToday, 0.3);
        $max = min($dueToday, 999999.99);
        $request->validate(['amount' => 'required|numeric|min:' . $min . '|max:' . $max]);
        return StripeGateway::checkout([['name' => "Deposit for Booking from {$booking->customer->full_name}", 'quantity' => 1, 'cost' => $request->amount]], $booking->token, 'Deposit', $booking->customer->id);
    }
}
