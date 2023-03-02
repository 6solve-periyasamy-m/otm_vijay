<?php

namespace App\Repository\Mailing;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\Payment;
use Auth;
use Faker\Factory as Faker;
use StringFormatter as Formatter;

class ShortCodeRepository
{

    public static function getFromString(string $key): ?array
    {
        return match ($key) {
            'order' => self::getOrderShortCodes(),
            'payment' => self::getPaymentShortCodes(),
            'settings' => self::getSettingShortCodes(),
            'order-customer' => self::getOrderCustomerShortCodes(),
            default => null,
        };
    }

    public static function getOrderShortCodes(Order $order = null): array
    {
        $faker = Faker::create();
        $customer = $order?->leadBooker->customer;
        $tour = $order?->tour;
        $nextPayment = $order?->next_installment;
        $data = [
            'LEAD_TITLE' => $customer?->title ?? $faker?->title,
            'LEAD_FIRST_NAME' => $customer->first_name ?? $faker?->firstName,
            'LEAD_MIDDLE_NAMES' => $customer->middle_names ?? $faker?->name,
            'LEAD_LAST_NAME' => $customer->last_name ?? $faker?->lastName,
            'LEAD_PASSPORT_EXPIRY_DATE' => Formatter::formatDate($customer->passport_expiry_date ?? $faker->date),
            'BOOKING_REFERENCE' => $order?->booking_reference ?? $faker->regexify('OTM[0-9]{12}[A-Z]{4}'),
            'ORDERED_ON' => Formatter::formatDate($order?->ordered_on ?? $faker->date),
            'DEPOSIT' => Formatter::formatCurrency($order?->deposit ?? $faker?->numberBetween(100, 1000)),
            'TOTAL_PAID' => Formatter::formatCurrency($order?->paid ?? $faker?->numberBetween(100, 1000)),
            'DUE_PAYMENT_AMOUNT' => Formatter::formatCurrency(isset($order) ? $nextPayment?->calculated_amount : $faker->numberBetween(100, 1000)),
            'DUE_PAYMENT_DATE' => Formatter::formatDate(isset($order) ? $nextPayment?->due_on: $faker->date),
            'TOUR_NAME' => $tour?->name ?? implode(' ', $faker?->words),
            'TOUR_DESCRIPTION' => $tour?->description ?? $faker?->sentence,
            'TOUR_START' => Formatter::formatDate($tour?->date_from ?? $faker->date),
            'TOUR_END' => Formatter::formatDate($tour?->date_to ?? $faker->date),
            'TOUR_BASE_PER_PERSON' => Formatter::formatDate($tour?->base_price_per_person ?? $faker->numberBetween(100, 1000)),
            'TOUR_SURCHARGE' => Formatter::formatDate($tour?->single_occupancy_surcharge ?? $faker->numberBetween(100, 1000)),
            'LATEST_INVOICE' => route('customer.invoice', ['reference' => $order?->booking_reference ?? 'reference',]),
            'PORTAL_LINK' => route('customer.portal'),
            'ATOL_LINK' => route('customer.atol', ['reference' => $order?->booking_reference ?? 'reference',]),
            'DETAILS_LINK' => route('customer.edit'),
        ];

        return array_merge($data, self::getSettingShortCodes());
    }

    public static function getSettingShortCodes(): array
    {
        return [
            'SETTING_COMPANY_NAME' => setting('company.name'),
            'SETTING_LOGO_URL' => asset(setting('company.logo')),
            'SETTING_COMPANY_ADDRESS_LINE_1' => setting('company.address.line_1'),
            'SETTING_COMPANY_ADDRESS_LINE_2' => setting('company.address.line_2'),
            'SETTING_COMPANY_ADDRESS_CITY' => setting('company.address.city'),
            'SETTING_COMPANY_ADDRESS_REGION' => setting('company.address.region'),
            'SETTING_COMPANY_ADDRESS_COUNTRY' => setting('company.address.country'),
            'SETTING_COMPANY_CONTACT_EMAIL' => setting('company.contact.email'),
            'SETTING_COMPANY_CONTACT_PHONE' => setting('company.contact.phone'),
            'SETTING_COMPANY_VAT' => setting('company.vat'),
            'SETTING_BOOKING_PREFIX' => setting('booking.prefix'),
            'SETTING_ATOL_ISSUER' => setting('atol.issuer'),
            'SETTING_ATOL_NUMBER' => setting('atol.number'),
            'SETTING_ATOL_STAMP' => asset(setting('atol.stamp')),
            'CURRENT_USER_NAME' => Auth::user()?->name ?? 'No User Found',
            'CURRENT_USER_EMAIL' => Auth::user()?->email ?? 'No User Found',
            'CURRENT_USER_IMAGE_URL' => Auth::user()?->avatar_url ?? 'No User Found',
        ];
    }

    public static function getPaymentShortCodes(Payment $payment = null): array
    {
        $faker = Faker::create();
        return array_merge([
            'PAYMENT_AMOUNT' => Formatter::formatCurrency($payment?->amount ?? $faker->numberBetween(100, 1000)),
            'PAYMENT_DATE' => Formatter::formatDate($payment?->paid_on ?? $faker->date),
            'PAYMENT_METHOD' => $payment?->paymentMethod->name ?? 'Demo Payment Method',
            'PAYMENT_TYPE' => $payment?->payment_type ?? 'Demo Payment Type'
        ], self::getOrderShortCodes($payment?->order));
    }

    public static function getOrderCustomerShortCodes(OrderCustomer $orderCustomer = null): array
    {
        $faker = Faker::create();
        $customer = $orderCustomer?->customer;
        return array_merge([
            'CUSTOMER_TITLE' => $customer?->title ?? $faker->title,
            'CUSTOMER_FIRST_NAME' => $customer?->first_name ?? $faker->firstName,
            'CUSTOMER_MIDDLE_NAMES' => $customer?->middle_names ?? $faker->firstName,
            'CUSTOMER_LAST_NAME' => $customer?->last_name ?? $faker->lastName,
            'CUSTOMER_PASSPORT_EXPIRY_DATE' => Formatter::formatDate($customer?->passport_expiry_date ?? $faker->date),
        ], self::getOrderShortCodes($orderCustomer?->order));
    }
}
