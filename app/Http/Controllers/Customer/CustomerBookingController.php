<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Gateways\StripeGateway;
use App\Models\AccommodationGroup;
use App\Models\ActivityInventoryTour;
use App\Models\Address;
use App\Models\AddressParent;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\FlightInventoryTour;
use App\Models\Merchandise;
use App\Models\RoomType;
use App\Models\Tour;
use App\Repository\AccommodationComponentRepository;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\CustomerBookingRepository;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Session;
use URL;

class CustomerBookingController extends Controller
{
    public function index(string $bookingUrl, string $token = null)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() <= 0) abort(404, 'That tour is out of stock');
        $booking = $this->getBooking($token);
        $customer = isset($booking) ? $booking->customer : CustomerAuthenticationRepository::getCustomer();
        return view('pages.customer.booking.customers', ['tour' => $tour, 'customer' => $customer, 'token' => $token,
            'leadTraveller' => CustomerBookingRepository::getLeadTraveller($booking),
            'additionalTravellers' => CustomerBookingRepository::getAdditionalTravellers($booking),
            'flights' => CustomerBookingRepository::getAvailableFlights($tour, $booking),
            'rooms' => AccommodationComponentRepository::getAvailableRoomTypes($tour),
            'groups' => AccommodationGroup::all(),
            'stock_control' => $tour->stock_control_active,
            'available' => $tour->stock - $tour->getUsedStock()]);
    }

    public function storeCustomers(Request $request, string $bookingUrl, string $token = null)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() < 1 + ($request->has('additional') ? sizeof($request->additional) : 0))
            abort(404, 'That tour is out of stock');
        $request->validate($this->getLeadBookerValidation());
        $loggedIn = CustomerAuthenticationRepository::getCustomer();
        $customer = Customer::where('email_address', $request->lead_email_address)->first();
        $bookedEmails = [strtolower(trim($request->lead_email_address)),];
        if ((isset($customer?->email_address) && isset($customer?->password))
            && (!isset($loggedIn) || $customer?->id !== $loggedIn?->id)) {
            Session::put('url.intended', URL::full());
            return redirect()->route('customer.login');
        }
        if (!isset($customer)) {
            $customer = Customer::make([
                'title' => $request->lead_title,
                'first_name' => $request->lead_first_name,
                'middle_names' => $request->lead_middle_names,
                'last_name' => $request->lead_last_name,
                'date_of_birth' => $request->lead_date_of_birth,
                'email_address' => $request->lead_email_address,
                'mobile_number' => $request->lead_mobile_number,
            ]);
        } else {
            $customer->update([
                'title' => $request->lead_title,
                'first_name' => $request->lead_first_name,
                'middle_names' => $request->lead_middle_names,
                'last_name' => $request->lead_last_name,
                'date_of_birth' => $request->lead_date_of_birth,
                'email_address' => $request->lead_email_address,
                'mobile_number' => $request->lead_mobile_number,
            ]);
        }
        $leadRoomType = RoomType::find($request->lead_room_type);
        $leadGroup = AccommodationGroup::find($request->lead_group);
        $rooming = [$leadGroup->id => ['id' => $leadRoomType->id, 'room_type' => $leadRoomType->id, 'available' => ($leadRoomType->maximum_occupancy-1),]];
        $homeAddress = LocationsRepository::storeAddress($customer->homeAddress, AddressParent::getParentId('customer'), $customer->first_name . ' ' . $customer->last_name, null,
            $request->lead_home_address_line_1, $request->lead_home_address_line_2, '', $request->lead_home_town, $request->lead_home_region, $request->lead_home_country, $request->lead_home_postcode);

        $billingAddress = LocationsRepository::storeAddress($customer->billingAddress, AddressParent::getParentId('customer'), $customer->first_name . ' ' . $customer->last_name, null,
            $request->lead_billing_address_line_1, $request->lead_billing_address_line_2, '', $request->lead_billing_town, $request->lead_billing_region, $request->lead_billing_country, $request->lead_billing_postcode);

        $customer->home_address_id = $homeAddress->id;
        $customer->billing_address_id = $billingAddress->id;
        $customer->save();
        $groupingData = [$customer->id => ['room' => $leadRoomType, 'group' => $leadGroup]];
        $booking = CustomerBookingRepository::generateBooking($tour, $customer, $leadRoomType, $leadGroup, $token);
        if ($request->has('additional')) {
            $errors = null;
            foreach ($request->input('additional') as $additional) {
                $customerErrors = $this->validateAdditional($additional);
                if ($additional['id'] !== 0) {
                    $traveller = Customer::find($additional['id']);
                }
                if (!isset($traveller) && !empty($additional['email_address'])) {
                    $traveller = Customer::where('email_address', $additional['email_address'])->first();
                }
                if (!empty($additional['email_address']) && in_array(strtolower(trim($additional['email_address'])), $bookedEmails)) {
                    return back()->withErrors(['msg' => 'You have used the email ' . $additional['email_address'] . ' for multiple customers. Please correct this.']);
                }
                if (!empty($additional['email_address'])) $bookedEmails[] = strtolower(trim($additional['email_address']));
                if (!isset($traveller)) {
                    $traveller = Customer::make([
                        'title' => $additional['title'],
                        'first_name' => $additional['first_name'],
                        'middle_names' => $additional['middle_names'],
                        'last_name' => $additional['last_name'],
                        'date_of_birth' => $additional['date_of_birth'],
                        'email_address' => $additional['email_address'],
                        'mobile_number' => $additional['mobile_number'],
                    ]);
                } else {
                    $traveller->update([
                        'title' => $additional['title'],
                        'first_name' => $additional['first_name'],
                        'middle_names' => $additional['middle_names'],
                        'last_name' => $additional['last_name'],
                        'date_of_birth' => $additional['date_of_birth'],
                        'email_address' => $additional['email_address'],
                        'mobile_number' => $additional['mobile_number'],
                    ]);
                }
                $roomType = RoomType::find($additional['room_type']);
                $groupId = $additional['group'];
                do {
                    if (key_exists($groupId, $rooming) && ($rooming[$groupId]['available'] < 1 || $rooming[$groupId]['room_type'] !== $roomType->id)) {
                        $groupId++;
                        continue;
                    }
                    break;
                } while (true);
                $group = AccommodationGroup::find($groupId);

                if (key_exists($groupId, $rooming)) {
                    $rooming[$groupId]['available'] = $rooming[$groupId]['available']--;
                } else {
                    $rooming[$groupId] = ['id' => $roomType->id, 'room_type' => $roomType->id, 'available' => $roomType->maximum_occupancy-1,];
                }
                if (!($customerErrors?->any())) {
                    if (!isset($traveller->home_address_id)) {
                        $homeAddress = Address::create(['name' => "$traveller->first_name $traveller->last_name (Home Address)", 'address_parent_id' => AddressParent::getParentId('customer'),]);
                        $traveller->home_address_id = $homeAddress->id;
                    }
                    if (!isset($traveller->billing_address_id)) {
                        $billingAddress = Address::create(['name' => "$traveller->first_name $traveller->last_name (Billing Address)", 'address_parent_id' => AddressParent::getParentId('customer'),]);
                        $traveller->billing_address_id = $billingAddress->id;
                    }
                    $traveller->save();
                    CustomerBookingRepository::addCustomerToBooking($booking, $traveller, $roomType, $group);
                    $groupingData[$traveller->id] = ['room' => $roomType, 'group' => $group,];
                }
                $errors = isset($errors) && $customerErrors?->any() ? $customerErrors->merge($errors) : $customerErrors;
            }
            if (!empty($errors)) {
                return redirect()->route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url, 'token' => $booking->token,])->withErrors($errors);
            }
        }
        $outbound = FlightInventoryTour::find($request->outbound);
        $inbound = FlightInventoryTour::find($request->inbound);
        if ((isset($outbound) && $outbound->tour_id !== $tour->id) ||
            (isset($inbound) && $inbound->tour_id !== $tour->id)) return back()->withErrors(['msg' => 'Those flights are not a part of this tour']);
        foreach ($booking->travellers as $traveller) {
            CustomerBookingRepository::addIncludedToBookingTraveller($traveller, $groupingData[$traveller->customer_id]['room'], $groupingData[$traveller->customer_id]['group']);
        }
        CustomerBookingRepository::selectFlights($booking, $outbound, $inbound);
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl, 'token' => $booking->token,]);
    }

    public function components(string $bookingUrl, string $token)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() < $booking->travellers()->count()) abort(404, 'That tour is out of stock');
        return view('pages.customer.booking.summary', array_merge(['tour' => $tour,'token' => $token, 'booking' => $booking,], CustomerBookingRepository::generateSummary($booking)));
    }

    public function purchaseAddon(string $bookingUrl, string $token, string $id, string $type)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() <= 0) abort(404, 'That tour is out of stock');
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        switch ($type) {
            case 'activity':
                $model = ActivityInventoryTour::find($id);
                if (!isset($model)) abort(404);
                $applied = CustomerBookingRepository::addBookingActivityAddon($booking, $model);
                if (!$applied) {
                    abort(404);
                }
                break;
            case 'extra':
                $model = Merchandise::find($id);
                if (!isset($model)) abort(404);
                $applied = CustomerBookingRepository::addBookingMerchandiseAddon($booking, $model);
                if (!$applied) {
                    abort(404);
                }
                break;
            default: abort(404);
        }
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl,'token' => $token,]);
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
        switch ($type) {
            case 'activity':
                $model = ActivityInventoryTour::find($id);
                if (!isset($model)) {
                    abort(404);
                }
                $applied = CustomerBookingRepository::removeBookingActivityAddon($booking, $model);
                if (!$applied) {
                    abort(404);
                }
                break;
            case 'extra':
                $model = Merchandise::find($id);
                if (!isset($model)) abort(404);
                $applied = CustomerBookingRepository::removeBookingMerchandiseAddon($booking, $model);
                if (!$applied) abort(404);
                break;
            default: abort(404);
        }
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl,'token' => $token,]);
    }

    public function payDeposit(Request $request, string $bookingUrl, string $token)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        if ($tour->stock_control_active && $tour->stock - $tour->getUsedStock() <= 0) abort(404, 'That tour is out of stock');
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        $values = CustomerBookingRepository::generateSummary($booking);
        $min = max($values['billing']['today'],0.3);
        $max = min($values['billing']['total'], 999999.99);
        $request->validate(['amount' => 'required|numeric|min:' . $min . '|max:' . $max]);
        return StripeGateway::checkout([['name' => "Deposit for Booking from {$booking->customer->full_name}", 'quantity' => 1, 'cost' => $request->amount]], $booking->token, 'Deposit', $booking->customer->id);
    }

    private function getTour(string $bookingUrl): ?Tour
    {
        return Tour::where('booking_form_url', $bookingUrl)->first();
    }

    private function getBooking(?string $token): ?Booking
    {
        if (!isset($token)) return null;
        return Booking::where('token', $token)->first();
    }

    private function getLeadBookerValidation(): array
    {
        return [
            'lead_title' => 'required',
            'lead_first_name' => 'required',
            'lead_last_name' => 'required',
            'lead_date_of_birth' => 'required',
            'lead_email_address' => 'required|confirmed',
            'lead_mobile_number' => 'required',
            'lead_home_address_line_1' => 'required',
            'lead_home_country' => 'required|exists:countries,id',
            'lead_home_postcode' => 'required',
            'lead_billing_address_line_1' => 'required',
            'lead_billing_country' => 'required|exists:countries,id',
            'lead_billing_postcode' => 'required',
        ];
    }

    private function validateAdditional(array $additional): ?MessageBag
    {
        $validator = Validator::make($additional, [
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required',
            'email_address' => 'nullable|email',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return null;
    }
}
