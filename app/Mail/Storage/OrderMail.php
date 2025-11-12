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
        $fakerNumber = $this->faker->numberBetween(100, 1000);

        $paymentDetails = collect([
                $order?->payment_details,
                $order?->quote?->payment_details,
                $order?->tour?->payment_details,
                setting('company.bank_transfer')
            ])->first(fn($value) => !empty($value));

        return [
            'LEAD_TITLE' => $customer?->title ?? $this->faker->title,
            'LEAD_FIRST_NAME' => $customer->first_name ?? $this->faker->firstName,
            'LEAD_MIDDLE_NAMES' => $customer->middle_names ?? $this->faker->name,
            'LEAD_LAST_NAME' => $customer->last_name ?? $this->faker->lastName,
            'LEAD_PASSPORT_EXPIRY_DATE' => f_date($customer->passport_expiry_date ?? $this->faker->date),
            'LEAD_CONTACT_NAME' => $order?->agent?->first_name ?? $order?->leadBooker?->customer?->first_name ?? $this->faker->name,
            'BOOKING_REFERENCE' => $order?->booking_reference ?? $this->faker->regexify('OTM[0-9]{12}[A-Z]{4}'),
            'ORDERED_ON' => f_date($order?->ordered_on ?? $this->faker->date),
            'ORDER_COST' => fr_currency($order?->cost ?? $fakerNumber, $order?->currency),
            'TOTAL_OWED' => fr_currency($order?->total ?? $fakerNumber, $order?->currency),
            'DEPOSIT' => fr_currency($order?->calculated_deposit ?? $fakerNumber, $order?->currency),
            'TOTAL_PAID' => fr_currency($order?->paid ?? $fakerNumber, $order?->currency),
            'TOTAL_REMAINING' => fr_currency($order?->remaining ?? $fakerNumber, $order?->currency),
            'DUE_PAYMENT_TOTAL' => fr_currency(isset($order) ? $nextPayment?->calculated_amount : $fakerNumber, $order?->currency),
            'DUE_PAYMENT_REMAINING' => fr_currency(isset($order) ? $nextPayment?->remaining : $fakerNumber, $order?->currency),
            'DUE_PAYMENT_DATE' => f_date(isset($order) ? $nextPayment?->due_on : $this->faker->date),
            'FINAL_PAYMENT_TOTAL' => fr_currency(isset($order) ? $finalPayment?->calculated_amount : $fakerNumber, $order?->currency),
            'FINAL_PAYMENT_REMAINING' => fr_currency(isset($order) ? $finalPayment?->remaining : $fakerNumber, $order?->currency),
            'FINAL_PAYMENT_DATE' => f_date(isset($order) ? $finalPayment?->due_on : $this->faker->date),
            'TOUR_NAME' => $tour?->name ?? implode(' ', $this->faker->words),
            'EVENT_NAME' => isset($tour) ? $tour->event?->name : implode(' ', $this->faker?->words),
            'EVENT_TYPE' => $tour && $tour->event && $tour->event->event_category ? ($tour->event->event_category->name === 'NORMAL' ? 'Child Event' : 'Parent Event'): implode(' ', $this->faker?->words),
            'TOUR_DESCRIPTION' => $tour?->description ?? $this->faker?->sentence,
            'TOUR_START' => f_date($tour?->date_from ?? $this->faker->date),
            'TOUR_END' => f_date($tour?->date_to ?? $this->faker->date),
            'TOUR_BASE_PER_PERSON' => fr_currency($tour?->base_price_per_person ?? $fakerNumber, $order?->currency),
            'TOUR_SURCHARGE' => fr_currency($tour?->single_occupancy_surcharge ?? $fakerNumber, $order?->currency),
            'LATEST_INVOICE' => route('customer.invoice', ['reference' => $order?->booking_reference ?? 'reference',]),
            'PORTAL_LINK' => route('customer.portal'),
            'ATOL_LINK' => route('customer.atol', ['reference' => $order?->booking_reference ?? 'reference',]),
            'BANK_DETAILS' => $paymentDetails,
            'DETAILS_LINK' => route('customer.edit'),
            ...(new SettingsMail())->getShortcodes(),
        ];
    }
}
