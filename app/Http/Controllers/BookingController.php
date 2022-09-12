<?php

namespace App\Http\Controllers;
use App\Http\Gateways\StripeGateway;
use App\Models\Order\Order;
use App\Models\System\Setting;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * bookingForm
     * returns a booking form from an order with token
     * or a new booking form that requests a tour be specified
     *
     * @param [type] $token
     * @return void
     */
    public function bookingForm($token = null)
    {

        die('booking form by token deprecated');

        if ($token) {
            $orders = new Order;
            $order = $orders->where('token', $token)->first();
            if ($order) {
                return view('pages.booking.form')->with(['order' => $order]);
            }
            abort(404);
        }
        if (config('app.setting.booking-selection')) {
            return view('pages.booking.form');
        }
        abort(403);
    }

    public function eventBookingForm($url) 
    {
        die('booking form by event URL deprecated');

        $event = Event::where('booking_url', $url)->first();
        if (empty($event)) {
            abort(404);
        }
        $tours = Tour::where('event_id', $event->id)->get();
        if (empty($tours)) {
            Log::error('There are no tours for event ', $event->toArray());
            abort(404);
        }
        return view('pages.booking.event.form')->with('event', $event);
    }

    public function tourBookingForm($url)
    {
        // TODO:
        // get the config and pass the logo and company name to the view
        $logoData = Setting::where('key', 'company.logo')->first();
        $companyData = Setting::where('key', 'company.name')->first();

        $tour = Tour::where('booking_form_url', $url)->first();
        if (empty($tour) || !$tour->is_active) {
            abort(404);
        }
        if ($tour->event_id) {
            try {
                $event = Event::findOrFail($tour->event_id);
            } catch (\Exception $e) {
                if (!config('app.setting.booking-selection')) {
                    abort(403);
                }
                return view('pages.booking.form');
            }
        } else {
            $event = null;
        }

        if (isset($tour) && isset($event)) {
            return view('pages.booking.tour.form')->with(['auth_user' => Auth::user(),
                'company' => ['logo' => $logoData->value, 'name' => $companyData->value],
                'tour' => $tour,
                'event' => $event]);
        }

        abort(404);
    }

    /***
     * payDeposit
     * Booking Form contains a form for accepting deposit
    *
     * Request:
     * $token    the booking token
     * $amount   expected format should contain currency character e.g. £600.00 (NB: £ uses 2 bytes)
     *           but 600.00 should also work, as should $600.00 or EURO600 but log anything not £
     */
    public function payDeposit(Request $request)
    {

        $request->validate(['token' => 'required|exists:bookings','tour_id' => 'required|exists:tours,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|regex:/^\d*\.?\d*$/',
            'currencyamount' => 'required|regex:/^([^\d]*?)(.*)$/']);
            // NB: currency amount only has to capture the currency symbol and can consider the rest as a string (number)
            // the regex commented out following should work to separate £ 1,000,000 .00 but it returns an error
            // 'currencyamount' => 'required|regex:/^([^\d]*?)([1-9]\d{0,2}(,\d{3})*)|0?(\.\d{1,2})$/']);
        $currencyAmount = $request->currencyamount;
        $amount = floatval($request->amount);

        // extract the currency symbol, everything before the first digit as the currency symbol
        $currencyRegex = '/^([^\d]*)([\d\,]*)(\.\d{2})?$/';
        $amountRegex = '/^(\d*\.?\d*)$/';
        // detect the currency symbol used
        preg_match($currencyRegex, $currencyAmount, $matched);
        $currency = $matched[1];
        // check the amount is a (decimal optional) number
        preg_match($amountRegex, $amount, $matched);

        $currencyLength = strlen($currency);
        // // If testing a new currency, ensure the length of the currency string is resolving correctly
        // // Log::debug('Check currency: '.$currencyAmount.' '.$currency. ' length='.$currencyLength);
        // // NB: £ uses two bytes
        $currency = substr($currencyAmount, 0, $currencyLength);

        // accept £999.99 or 999.99
        $checkamount = 0;
        if ($currency === '£') {
           $checkamount = floatval(substr($currencyAmount, $currencyLength, strlen($currencyAmount) - $currencyLength));
        }
        if ($currency !== '£') {
            Log::warning('BookingController::payDeposit() WARNING: unsupported currency detected:'.$currency);
        }
        if ($checkamount !== $amount) {
            Log::warning('BookingContorller::payDeposit() WARNING: currency '.$currency. ' amount '.$checkamount.' mismatched with amount '. $amount);
        }

        $bookingRepository = new BookingRepository();
        $booking = $bookingRepository->findBookingByToken($request->token);
        if (!$booking) {
          return response(['success' => false, 'error' => 'Non-existant booking']);
        }
        $customer = CustomerRepository::lookup($booking->customer_id);

        if ($amount < 0.01) {
            Log::debug('BookingController::payDeposit() currency values',[$currency, $currencyAmount, $currencyLength, $amount, $booking, $customer]);
            throw new Exception('BookingController::payDeposit did not resolve to a deposit amount');
        }

        $bookingRepository->setStatusDepositCheckout($booking);
        Log::info('Booking: sending deposit request to StripeGateway:', [[['name' => "Deposit for Booking from $customer->full_name", 'quantity' => 1, 'cost' => $amount]], $booking->token,'Deposit']);

        $redirect = setting('booking.success.redirect', route('payment.gateway.stripe.success'));

        return StripeGateway::checkoutOld([['name' => "Deposit for Booking from $customer->full_name", 'quantity' => 1, 'cost' => $amount]], $booking->token, 'Deposit', $customer->id, $redirect);
    }

    /**
     * A new booking creates an order with dependencies
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newOrder = new Order;
        
        return (var_dump($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
