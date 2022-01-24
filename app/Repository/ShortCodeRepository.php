<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\Payment;
use Faker\Factory as Faker;

interface ShortCodeRepositoryInterface {
    public static function getOrderShortCodes(Order $order = null);
    public static function getPaymentShortCodes(Payment $payment = null);
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
            'DUE_PAYMENT_AMOUNT' => !isset($order) ? $faker->numberBetween(100, 1000) : $nextPayment['amount'],
            'DUE_PAYMENT_DATE' => !isset($order) ? $faker->date : $nextPayment['due'],
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

        return array_merge($data, self::getSettingShortCodes());
    }

    public static function getPaymentShortCodes(Payment $payment = null) {
        $faker = Faker::create();
        return array_merge([
            'PAYMENT_AMOUNT' => !isset($payment) ? $faker->numberBetween(100, 1000) : $payment->amount,
            'PAYMENT_DATE' => !isset($payment) ? $faker->date : $payment->paid_on,
            'PAYMENT_METHOD' =>!isset($payment) ? 'Demo Payment Method' :  $payment->paymentMethod->name,
            'PAYMENT_TYPE' => !isset($payment) ? 'Demo Payment Type' : $payment->payment_type
        ], self::getOrderShortCodes(isset($payment) ? $payment->order : null));
    }

    public static function getSettingShortCodes() {
        return [
            'SETTING_COMPANY_NAME' => SettingsRepository::get('company.name'),
            'SETTING_COMPANY_LOGO' => asset(SettingsRepository::get('company.logo')),
            'SETTING_COMPANY_ADDRESS_LINE_1' => SettingsRepository::get('company.address.line_1'),
            'SETTING_COMPANY_ADDRESS_LINE_2' => SettingsRepository::get('company.address.line_2'),
            'SETTING_COMPANY_ADDRESS_CITY' => SettingsRepository::get('company.address.city'),
            'SETTING_COMPANY_ADDRESS_REGION' => SettingsRepository::get('company.address.region'),
            'SETTING_COMPANY_ADDRESS_COUNTRY' => SettingsRepository::get('company.address.country'),
            'SETTING_COMPANY_CONTACT_EMAIL' => SettingsRepository::get('company.contact.email'),
            'SETTING_COMPANY_CONTACT_PHONE' => SettingsRepository::get('company.contact.phone'),
            'SETTING_COMPANY_VAT' => SettingsRepository::get('company.vat'),
            'SETTING_BOOKING_PREFIX' => SettingsRepository::get('booking.prefix'),
            'SETTING_ATOL_ISSUER' => SettingsRepository::get('atol.issuer'),
            'SETTING_ATOL_NUMBER' => SettingsRepository::get('atol.number'),
            'SETTING_ATOL_STAMP' => asset(SettingsRepository::get('atol.stamp')),
        ];
    }
}
