<?php

namespace App\Repository;

use App\Models\Order;
use Faker\Factory as Faker;

interface ShortCodeRepositoryInterface {
    public static function getOrderShortCodes(Order $order = null);
}

class ShortCodeRepository implements ShortCodeRepositoryInterface
{
    public static function getOrderShortCodes(Order $order = null)
    {
        $faker = Faker::create();
        $customer = isset($order) ? $order->leadBooker->customer : null;
        $tour = isset($order) ? $order->tour : null;
        $nextPayment = isset($order) ? OrderRepository::getNextPaymentDetails($order) : null;
        $data = [
            'TITLE' => !isset($order) ? $faker->title : $customer->title,
            'FIRST_NAME' => !isset($order) ? $faker->firstName : $customer->first_name,
            'MIDDLE_NAMES' => !isset($order) ? $faker->firstName : $customer->middle_names,
            'LAST_NAME' => !isset($order) ? $faker->lastName : $customer->last_name,
            'PASSPORT_EXPIRY_DATE' => !isset($order) ? $faker->date : $customer->passport_expiry_date,
            'BOOKING_REFERENCE' => !isset($order) ? $faker->regexify('OTM[0-9]{12}[A-Z]{4}') : $order->booking_reference,
            'ORDERED_ON' => !isset($order) ? $faker->date : $order->ordered_on,
            'TOTAL_PAID' => !isset($order) ? $faker->numberBetween(100, 1000) : OrderRepository::getTotalPaid($order),
            'PAYMENT_AMOUNT' => !isset($order) ? $faker->numberBetween(100, 1000) : $nextPayment['amount'],
            'PAYMENT_DUE' => !isset($order) ? $faker->date : $nextPayment['due'],
            'TOUR_NAME' => !isset($order) ? implode(' ', $faker->words) : $tour->name,
            'TOUR_DESCRIPTION' => !isset($order) ? $faker->sentence : $tour->description,
            'TOUR_START' => !isset($order) ? $faker->date : $tour->date_from,
            'TOUR_END' => !isset($order) ? $faker->date : $tour->date_to,
            'TOUR_BASE_PER_PERSON' => !isset($order) ? $faker->numberBetween(100, 1000) : $tour->base_price_per_person,
            'TOUR_DEPOSIT' => !isset($order) ? $faker->numberBetween(100, 1000) : $tour->deposit,
            'TOUR_SURCHARGE' => !isset($order) ? $faker->numberBetween(100, 1000) : $tour->single_occupancy_surcharge,
            'LATEST_INVOICE' => !isset($order) ? $faker->url : route('orders.invoice.latest', ['order' => $order,]), // TODO: Link to customers invoices
            'PORTAL_LINK' => !isset($order) ? $faker->url : route('customer.portal', ['customer' => $customer,]),
            'ATOL_LINK' => !isset($order) ? $faker->url : route('customer.atol', ['customer' => $customer,]),
            'DETAILS_LINK' => !isset($order) ? $faker->url : route('customer.details', ['customer' => $customer,]),
        ];

        return $data;
    }
}
