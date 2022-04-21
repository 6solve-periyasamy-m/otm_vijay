<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AccommodationGroup;
use App\Models\AddressParent;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\RoomType;
use App\Models\Tour;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\CustomerBookingRepository;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;

class CustomerBookingController extends Controller
{
    public function index(string $bookingUrl, string $token = null)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        $booking = $this->getBooking($token);
        $customer = isset($booking) ? $booking->customer : CustomerAuthenticationRepository::getCustomer();
        return view('pages.customer.booking.customers', ['tour' => $tour, 'customer' => $customer,]);
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
        $homeAddress = LocationsRepository::storeAddress($customer->homeAddress, AddressParent::getParentId('customer'),$customer->first_name . ' ' . $customer->last_name, null,
        $request->lead_home_address_line_1, $request->lead_home_address_line_2, '', $request->lead_home_town, $request->lead_home_region, $request->lead_home_country, $request->lead_home_postcode);
        $billingAddress = LocationsRepository::storeAddress($customer->billingAddress, AddressParent::getParentId('customer'),$customer->first_name . ' ' . $customer->last_name, null,
        $request->lead_billing_address_line_1, $request->lead_billing_address_line_2, '', $request->lead_billing_town, $request->lead_billing_region, $request->lead_billing_country, $request->lead_billing_postcode);
        $customer->home_address_id = $homeAddress->id;
        $customer->billing_address_id = $billingAddress->id;
        $customer->save();
        $booking = CustomerBookingRepository::generateBooking($tour, $customer, RoomType::find(1), AccommodationGroup::find(1));
        return redirect()->route('customer-booking.summary', ['bookingUrl' => $bookingUrl, 'token' => $booking->token,]);
    }

    public function components(string $bookingUrl, string $token)
    {
        $tour = $this->getTour($bookingUrl);
        if (!isset($tour) || !$tour->is_active) abort(404);
        $booking = $this->getBooking($token);
        if (!isset($booking) || $booking->tour_id !== $tour->id) abort(404);
        return view('pages.customer.booking.summary', array_merge(['tour' => $tour,], CustomerBookingRepository::generateSummary($booking)));
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
}
