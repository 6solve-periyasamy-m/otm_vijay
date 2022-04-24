<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\CustomerBookingRepository;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Log;

class CustomerBookingController extends Controller
{
    public function index(string $bookingUrl, string $token = null)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        $booking = $this->getBooking($token);
        $customer = isset($booking) ? $booking->customer : CustomerAuthenticationRepository::getCustomer();
        return view('pages.customer.booking.customers', ['tour' => $tour, 'customer' => $customer, 'token' => $token,
            'additionalTravellers' => CustomerBookingRepository::getAdditionalTravellers($booking),
            'flights' => CustomerBookingRepository::getAvailableFlights($tour, $booking),]);
    }

    public function storeCustomers(Request $request, string $bookingUrl, string $token = null)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        $request->validate($this->getLeadBookerValidation());
        $loggedIn = CustomerAuthenticationRepository::getCustomer();
        $customer = Customer::where('email_address', $request->lead_email_address)->first();
        if ((isset($customer?->email_address) && isset($customer?->password))
            && (!isset($loggedIn) || $customer?->id !== $loggedIn?->id)) {
            return back()->withErrors(['msg' => 'That email address already exists. If it is yours, please log in.']);
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
        $homeAddress = LocationsRepository::storeAddress($customer->homeAddress, AddressParent::getParentId('customer'), $customer->first_name . ' ' . $customer->last_name, null,
            $request->lead_home_address_line_1, $request->lead_home_address_line_2, '', $request->lead_home_town, $request->lead_home_region, $request->lead_home_country, $request->lead_home_postcode);

        $billingAddress = LocationsRepository::storeAddress($customer->billingAddress, AddressParent::getParentId('customer'), $customer->first_name . ' ' . $customer->last_name, null,
            $request->lead_billing_address_line_1, $request->lead_billing_address_line_2, '', $request->lead_billing_town, $request->lead_billing_region, $request->lead_billing_country, $request->lead_billing_postcode);

        $customer->home_address_id = $homeAddress->id;
        $customer->billing_address_id = $billingAddress->id;
        $customer->save();

        $booking = CustomerBookingRepository::generateBooking($tour, $customer, RoomType::find(1), AccommodationGroup::find(1));
        $outbound = FlightInventoryTour::find($request->outbound);
        $inbound = FlightInventoryTour::find($request->inbound);
        if ((isset($outbound) && $outbound->tour_id !== $tour->id) ||
            (isset($inbound) && $inbound->tour_id !== $tour->id)) return back()->withErrors(['msg' => 'Those flights are not a part of this tour']);
        CustomerBookingRepository::selectFlights($booking, $outbound, $inbound);
        if ($request->has('additional')) {
            $errors = null;
            foreach ($request->input('additional') as $additional) {
                //dd($additional);
                $customerErrors = $this->validateAdditional($additional);
                if ($additional['id'] !== 0) {
                    $traveller = Customer::find($additional['id']);
                }
                if (!isset($traveller) && !empty($additional['email_address'])) {
                    $traveller = Customer::where('email_address', $additional['email_address']);
                }
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
                    CustomerBookingRepository::addCustomerToBooking($booking, $traveller, RoomType::find(1), AccommodationGroup::find(1));
                }
                $errors = isset($errors) && $customerErrors?->any() ? $customerErrors->merge($errors) : $customerErrors;
            }
            if (!empty($errors)) {
                return redirect()->route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url, 'token' => $booking->token,])->withErrors($errors);
            }
        }
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl, 'token' => $booking->token,]);
    }

    public function components(string $bookingUrl, string $token)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        return view('pages.customer.booking.summary', array_merge(['tour' => $tour,'token' => $token,], CustomerBookingRepository::generateSummary($booking)));
    }

    public function purchaseAddon(string $bookingUrl, string $token, string $id, string $type)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
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
            'email_address' => 'nullable|confirmed|email',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return null;
    }
}
