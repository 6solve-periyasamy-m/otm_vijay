<?php

namespace App\Repository;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Exceptions\RoomingFailedException;
use App\Models\Booking\AccommodationGroup;
use App\Models\Booking\Booking;
use App\Models\Customer\Group;
use App\Models\Location\Address;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use Exception;
use Illuminate\Support\Facades\Log;

interface BookingRepositoryInterface {
    public function __construct();
    public function findBookingByToken($token);
    public static function findBooking($token);
    public function create($customer_id, $tour_id, $token, $name);
    public function setStatusDepositCheckout(Booking $booking);
    public static function convertBookingToOrder(Booking $booking): Order;
}

class BookingRepository implements BookingRepositoryInterface
{
    protected $model;
    protected $debug;

    public function __construct()
    {
        $this->model = new Booking();
        $this->debug = false;
    }

    /**
     * findBookingByToken
     *
     * @param [type] $token
     * @return booking
     */
    public function findBookingByToken($token)
    {

        $booking = $this->model->where('token', $token)->first();

        $this->debug && Log::debug('findBookingByToken:', [$token, $booking, isset($booking->customer), isset($booking->customer_id)]);

        if (isset($booking) && isset($booking->customer_id)) {
            $booking->customer->home_address = Address::find($booking->customer->home_address_id);
            $booking->customer->billing_address = Address::find($booking->customer->billing_address_id);
            return $booking;
        }
        Log::warning('BookingRepository::findBookingByToken: token was not found', [$token]);

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
        try {
            $booking = Booking::where('token', $token)->first();
            // Log::debug('BookingRepository::static findBooking', [$booking]);
            return $booking;
        } catch (Exception $e) {
            Log::error("error finding booking for $token", $e->getMessage());
        }
        return null;
        //return (new BookingRepository)->findBookingByToken($token);
    }

    /**
     * create a new booking
     *
     * @param INTEGER $customer_id
     * @param INTEGER $tour_id
     * @param STRING $token
     * @return Booking
     */
    public function create($customer_id, $tour_id, $token, $name)
    {
        $this->model->customer_id = $customer_id;
        $this->model->tour_id = $tour_id;
        $this->model->token =$token;
        $this->model->name = $name;
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

        $order->repository->resetInstallments();

        $customers = [];
        $order->orderCustomers()->save($leadBooker);

        $customers[$booking->customer_id] = $leadBooker;
        $order->lead_booker_id = $leadBooker->id;
        $order->booking_reference = Order::generateBookingReference($order);
        $order->save();
        //event(new OrderCreatedEvent($order));

        $leadBooker->repository->addAllIncluded();
        foreach ($booking->travellers as $traveller) {
            if (array_key_exists($traveller->customer_id, $customers)) continue;
            $customer = OrderCustomer::make([
                'customer_id' => $traveller->customer_id,
                'tour_cost' => $tour->base_price_per_person,
                'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
            ]);
            $order->orderCustomers()->save($customer);
            $customers[$traveller->customer_id] = $customer;
            event(new OrderCustomerCreatedEvent($customer));
            $customer->repository->addAllIncluded();
        }

        //self::processComponent($customers, 'activities', $booking);
        //self::processComponent($customers, 'flights', $booking);
        //self::processComponent($customers, 'transports', $booking);
        self::processAccommodation($customers, $booking, $order);
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

    private static function processAccommodation(array $customers, Booking $booking, Order $order)
    {
        $groups = [];
        foreach ($booking->accommodation as $bookingAccommodation) {
            $customer = $customers[$bookingAccommodation->customer_id];
            $groupName = $bookingAccommodation->group_id > 0 ? AccommodationGroup::find($bookingAccommodation->group_id)->name : $customer->customer_name;
            $group = key_exists($bookingAccommodation->group_id, $groups) ? $groups[$bookingAccommodation->group_id]
                : Group::create(['name' => $groupName, 'room_type_id' => $bookingAccommodation->room_type_id,]);
            $group->orderCustomers()->save($customer);
            $groups[$bookingAccommodation->group_id] = $group;
        }
        foreach ($groups as $group) {
            try {
                RoomingRepository::addRoomsToGroup($order, $group);
            } catch (RoomingFailedException $e) { Log::error($e); }
        }
    }
}
