<?php

namespace App\Repository;

use App\Models\Action;
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
        return $this->model->where('token', $token)->first();
    }
    public function create($customer_id, $tour_id, $token)
    {
        $this->model->customer_id = $customer_id;
        $this->model->tour_id = $tour_id;
        $this->model->token = $token;

        $booking = $this->model->save();

        return $booking;
    }
}