<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrderCustomer;
use Exception;
use App\Models\Action;

use App\Models\Address;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

interface BookingRepositoryInterface {
    public function __construct();
    public function findBookingByToken($token);
    public static function findBooking($token);
    public function create($customer_id, $tour_id, $token);
    public function setStatusDepositCheckout(Booking $booking);
    public static function convertBookingToOrder(Booking $booking): Order;
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
        if (isset($booking) && isset($booking->customer)) {
            $booking->customer->home_address = Address::find($booking->customer->home_address_id);
            $booking->customer->billing_address = Address::find($booking->customer->billing_address_id);
            return $booking;
        }
        Log::debug('findBookingByToken: token not found', [$token]);

        return null;
    }

    /**
     * findBooking (static accessor)
     *
     * @param [type] $token
     * @return Booking
     */
    public static function findBooking($token)
    {
        return (new BookingRepository)->findBookingByToken($token);
    }

    /**
     * create a new booking
     *
     * @param INTEGER $customer_id
     * @param INTEGER $tour_id
     * @param STRING $token
     * @return Booking
     */
    public function create($customer_id, $tour_id, $token)
    {
        $this->model->customer_id = $customer_id;
        $this->model->tour_id = $tour_id;
        $this->model->token = $token;
        $this->model->status = 'New';

        $booking = $this->model->save();
        if ($booking) {
            return $this->model;
        }
        throw new Exception('Can not create booking record');
    }

    public function setStatusDepositCheckout($booking)
    {
        $booking->status = 'Deposit Processing';
        $booking->save();
    }
    /**
     * convertBookingToOrder
     *
     * @param Booking $booking
     * @return Order
     */
    public static function convertBookingToOrder(Booking $booking): Order
    {
        $tour = $booking->tour;
        $order = Order::create([
            'tour_id' => $booking->tour_id,
            'ordered_on' => now(),
            'deposit' => $tour->deposit,
            'invoice_footer' => $tour->invoice_footer
        ]);
        $leadBooker = OrderCustomer::make([
            'customer_id' => $booking->customer_id,
            'tour_cost' => $tour->base_price_per_person,
            'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
        ]);
        $customers = [];
        $order->orderCustomers()->save($leadBooker);
        $customers[$booking->customer_id] = $leadBooker;
        $order->lead_booker_id = $leadBooker->id;
        $order->booking_reference = Order::generateBookingReference($order);
        $order->save();

        foreach ($booking->travellers as $traveller) {
            if (array_key_exists($traveller->customer_id, $customers)) continue;
            $customer = OrderCustomer::make([
                'customer_id' => $traveller->customer_id,
                'tour_cost' => $tour->base_price_per_person,
                'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
            ]);
            $order->orderCustomers()->save($customer);
            $customers[$traveller->customer_id] = $customer;
        }
        self::processComponent($customers, 'accommodation', $booking);
        self::processComponent($customers, 'activities', $booking);
        self::processComponent($customers, 'flights', $booking);
        self::processComponent($customers, 'transports', $booking);
        self::setStatus($booking, 'Deposit Accepted');
        return $order;
    }

    private static function setStatus($booking, String $str)
    {
        $bookingObject = (new BookingRepository)->findBookingByToken($booking->token);
        $bookingObject->status = $str;
        $bookingObject->save();
    }

    private static function processComponent(array $customers, string $component, Booking $booking)
    {
        foreach ($booking->{$component} as $bookingComponent) {
            $bookingComponent->tourComponent->addToOrder($customers[$bookingComponent->customer_id]);
        }
    }
}
