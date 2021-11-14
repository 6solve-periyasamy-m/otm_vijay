<?php

namespace App\Repository;

use App\Models\Action;
use App\Models\Address;

use App\Models\Booking;
use Illuminate\Support\Facades\Log;

interface BookingRepositoryInterface {
}

class BookingRepository implements BookingRepositoryInterface
{
    protected $model;
    public function __construct()
    {
        $this->model = new Booking();
    }   
    public function findBookingByToken($token)
    {
        $booking = $this->model->where('token', $token)->first();
        if (isset($booking)) {
            $booking->customer->home_address = Address::find($booking->customer->home_address_id);
            $booking->customer->billing_address = Address::find($booking->customer->billing_address_id);
        
            return $booking;
        }
        return null;
    }
    public function create($customer_id, $tour_id, $token)
    {
        Log::debug('============== create a booking with ', [$customer_id, $tour_id, $token]);
        $this->model->customer_id = $customer_id;
        $this->model->tour_id = $tour_id;
        $this->model->token = $token;
        $booking = $this->model->save();
        Log::debug('_______________ saving booking', [$booking]);

        return $booking;
    }
}