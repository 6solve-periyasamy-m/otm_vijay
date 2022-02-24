<?php

namespace App\Http\Controllers;
use Exception;

use App\Models\Tour;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Gateways\StripeGateway;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;

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
        $tour = Tour::where('booking_form_url', $url)->first();
        if (empty($tour)) {
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
            return view('pages.booking.tour.form')->with(['tour' => $tour, 'event' => $event]);
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

        $request->validate(['token' => 'required|exists:bookings', 'amount' => 'required| regex:/^([^\d]*)\d*(\.\d{2})?$/']);
        $amountCurrency = $request->amount;

        // extract the currency symbol, everything before the first digit as the currency symbol
        $currencyRegex = '/^([^\d]*)\d*(\.\d{2})?$/';
        preg_match($currencyRegex, $amountCurrency, $matched);
        $currency = $matched[1];
        if ($currency !== '£') {
          Log::warning('BookingController::payDeposit() WARNING: unsupported currency detected:'.$currency); 
        }
        $currencyLength = strlen($currency);

        // If testing a new currency, ensure the length of the currency string is resolving correctly
        // Log::debug('Check currency: '.$amountCurrency.' '.$currency. ' length='.$currencyLength);

        // NB: £ uses two bytes
        $currency = substr($amountCurrency, 0, $currencyLength);
        // accept £999.99 or 999.99
        if ($currency === '£') {
          $amount = floatval(substr($request->amount, $currencyLength, strlen($request->amount) - $currencyLength ));
        } else if (($currency !== '£') && fmod(floatval($request->amount), 1) === 0.00) {
          $amount = floatval($request->amount);
        // log incorrect amount formatting due to unexpected changed
        } else if (($currency !== '£') && fmod(floatval($request->amount), 1) !== 0.00) {
          Log::error('deposit received but not in £', [$request->amount]);
          throw new Exception('Deposit received but value is not £');
        } else {
            Log::error('Deposit format incorrect, expect decimal value', [$request->amount]);
          throw new Exception('Deposit received appears not valid');
        }
        $bookingRepository = new BookingRepository();
        $booking = $bookingRepository->findBookingByToken($request->token);
        if (!$booking) {
          return response(['success' => false, 'error' => 'Non-existant booking']);
        }
        $customer = CustomerRepository::lookup($booking->customer_id);

        if ($amount < 0.01) {
            Log::debug('BookingController::payDeposit() currency values',[$currency, $amountCurrency, $currencyLength, $amount, $booking, $customer]); 
            throw new Exception('BookingController::payDeposit did not resolve to a deposit amount'); 
        }

        $bookingRepository->setStatusDepositCheckout($booking);
        Log::info('Booking: sending deposit request to StripeGateway:', [[['name' => "Deposit for Booking from $customer->full_name", 'quantity' => 1, 'cost' => $amount]], $booking->token,'Deposit']);

        return StripeGateway::checkout([['name' => "Deposit for Booking from $customer->full_name", 'quantity' => 1, 'cost' => $amount]], $booking->token, 'Deposit', $customer->id);
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
