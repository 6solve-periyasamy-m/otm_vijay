<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\BookingTraveller;

interface BookingTravellerRepositoryInterface
{
    public function __construct();
    public function get($id);
    public function getGroup($booking_id);
    public function create($booking_id, $customer_id);
    public function remove($booking_id, $customer_id);
}

class BookingTravellerRepository implements BookingTravellerRepositoryInterface
{
    protected $model;
    private $current;

    public function __construct()
    {
        $this->model = new BookingTraveller();
        $this->current = null;
    }

    /**
     * get: gets an additional traveller by ID
     *
     * @param [type] $id
     * @return void
     */
    public function get($id)
    {
        $this->current = $this->model->findOrFail($id);

        return $this->current;
    }

    private function find($booking_id, $customer_id)
    {
        $found = $this->model->where('booking_id', $booking_id)
            ->where('customer_id', $customer_id)
            ->count();

        return $found;
    }

    /**
     * getGroup - returns an array of additional travellers associated with a booking
     *
     * @param [type] $customer_id
     * @return Collection
     */
    public function getGroup($booking_id)
    {
        $customers = $this->model->where('booking_id', $booking_id)->get();
        return $customers;
    }

    private function getId($a)
    {
        return $a->id;
    }
    public function getIds($booking_id)
    {
        $customers = $this->getGroup($booking_id);
        Log::debug('>>>>>>>customers', [$customers]);
        $ids = $customers->map(function($item, $key) {
            return $item->customer_id;
        });
        return $ids;

    }   

    public function create($booking_id, $customer_id)
    {
        if ($this->find($booking_id, $customer_id)) {
            Log::debug('BookingTravellerRepo request to create a dup record', [$booking_id, $customer_id]);
            return false;
        }
        $this->model->booking_id = $booking_id;
        $this->model->customer_id = $customer_id;
        $this->current = $this->model->save();
        return $this->current;
    }

    public function remove($booking_id, $customer_id)
    {
        return $this->model->where('booking_id', $booking_id)->where('customer_id', $customer_id)->delete();
    }

}
