<?php

namespace App\Http\Controllers;

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
            $order = $orders->where(['token', $token])->first();
            if ($order) {
                return view('bookingForm')->with(['order' => $order]);
            }
        } 
        return view('bookingForm');
    }

    public function tourBookingForm($tourUrl)
    {
        $tour = Tour::where('booking_form_url', $tourUrl)->first();
        try {
            $event = Event::findOrFail($tour->event_id);
        } catch(\Exception $e) {
            return view('bookingForm');
        }

        if ($tour && $event) {
            return view('tourBookingForm')->with(['tour' => $tour, 'event' => $event]);
        }
        return view('bookingForm');
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
