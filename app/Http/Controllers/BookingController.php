<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Tour;
use App\Models\Event;

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
        try {
            $event = Event::findOrFail($tour->event_id);
        } catch(\Exception $e) {
            if (!config('app.setting.booking-selection')) {
                abort(403);
            }
            return view('pages.booking.form');
        }

        if (isset($tour) && isset($event)) {
            return view('pages.booking.tour.form')->with(['tour' => $tour, 'event' => $event]);
        }

        abort(404);
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
