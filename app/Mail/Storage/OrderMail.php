<?php

namespace App\Mail\Storage;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\Payment;

class OrderMail extends TemplatedMail
{
    public function getShortcodes($model = null): array
    {
        if ($model instanceof OrderCustomer || $model instanceof Payment) {
            $order = $model->order;
        } elseif ($model instanceof Order) {
            $order = $model;
        } else {
            $order = null;
        }
        $customer = $order?->leadBooker->customer;
        $nextPayment = $order?->next_installment;
        $finalPayment = $order?->repository->generateRemainingOrderInstallment();
        $tour = $order?->tour;
        return [
            'LEAD_TITLE' => $customer?->title ?? $this->faker->title,
            'LEAD_FIRST_NAME' => $customer->first_name ?? $this->faker->firstName,
            'LEAD_MIDDLE_NAMES' => $customer->middle_names ?? $this->faker->name,
            'LEAD_LAST_NAME' => $customer->last_name ?? $this->faker->lastName,
            'LEAD_PASSPORT_EXPIRY_DATE' => f_date($customer->passport_expiry_date ?? $this->faker->date),
            'LEAD_CONTACT_NAME' => $order?->agent?->name ?? $order?->organization?->name ?? $order?->leadBooker?->lead_booker_name ?? $this->faker->name,
            'BOOKING_REFERENCE' => $order?->booking_reference ?? $this->faker->regexify('OTM[0-9]{12}[A-Z]{4}'),
            'ORDERED_ON' => f_date($order?->ordered_on ?? $this->faker->date),
            'ORDER_COST' => f_currency($order?->cost ?? $this->faker->numberBetween(100, 1000)),
            'TOTAL_OWED' => f_currency($order?->total ?? $this->faker->numberBetween(100, 1000)),
            'DEPOSIT' => f_currency($order?->deposit ?? $this->faker->numberBetween(100, 1000)),
            'TOTAL_PAID' => f_currency($order?->paid ?? $this->faker->numberBetween(100, 1000)),
            'TOTAL_REMAINING' => f_currency($order?->remaining ?? $this->faker->numberBetween(100, 1000)),
            'DUE_PAYMENT_TOTAL' => f_currency(isset($order) ? $nextPayment?->calculated_amount : $this->faker->numberBetween(100, 1000)),
            'DUE_PAYMENT_REMAINING' => f_currency(isset($order) ? $nextPayment?->remaining : $this->faker->numberBetween(100, 1000)),
            'DUE_PAYMENT_DATE' => f_date(isset($order) ? $nextPayment?->due_on : $this->faker->date),
            'FINAL_PAYMENT_TOTAL' => f_currency(isset($order) ? $finalPayment?->calculated_amount : $this->faker->numberBetween(100, 1000)),
            'FINAL_PAYMENT_REMAINING' => f_currency(isset($order) ? $finalPayment?->remaining : $this->faker->numberBetween(100, 1000)),
            'FINAL_PAYMENT_DATE' => f_date(isset($order) ? $finalPayment?->due_on : $this->faker->date),
            'TOUR_NAME' => $tour?->name ?? implode(' ', $this->faker->words),
            'EVENT_NAME' => isset($tour) ? $tour->event?->name : implode(' ', $this->faker?->words),
            'TOUR_DESCRIPTION' => $tour?->description ?? $this->faker?->sentence,
            'TOUR_START' => f_date($tour?->date_from ?? $this->faker->date),
            'TOUR_END' => f_date($tour?->date_to ?? $this->faker->date),
            'TOUR_BASE_PER_PERSON' => f_currency($tour?->base_price_per_person ?? $this->faker->numberBetween(100, 1000)),
            'TOUR_SURCHARGE' => f_currency($tour?->single_occupancy_surcharge ?? $this->faker->numberBetween(100, 1000)),
            'LATEST_INVOICE' => route('customer.invoice', ['reference' => $order?->booking_reference ?? 'reference',]),
            'PORTAL_LINK' => route('customer.portal'),
            'ATOL_LINK' => route('customer.atol', ['reference' => $order?->booking_reference ?? 'reference',]),
            'DETAILS_LINK' => route('customer.edit'),
            ...(new SettingsMail())->getShortcodes(),
        ];
    }
}
