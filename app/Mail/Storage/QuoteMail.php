<?php

namespace App\Mail\Storage;

use App\Models\Quote\Quote;
use App\Models\Quote\SentQuote;

class QuoteMail extends TemplatedMail
{

    public function getShortcodes($model = null): array
    {
        if ($model instanceof SentQuote) {
            $sent = $model;
            $quote = $sent->quote;
        } elseif ($model instanceof Quote) {
            $quote = $model;
            $sent = $quote->sentQuotes()->latest()->first();
        } else {
            $quote = null;
        }
        $customer = $quote?->leadTraveller?->customer;
        return [
            'LEAD_TITLE' => $customer?->title ?? $this->faker?->title,
            'LEAD_FIRST_NAME' => $customer?->first_name ?? $this->faker?->firstName,
            'LEAD_MIDDLE_NAMES' => $customer?->middle_names ?? $this->faker?->name,
            'LEAD_LAST_NAME' => $customer?->last_name ?? $this->faker?->lastName,
            'BOOKING_REFERENCE' => $quote?->ref ?? $this->faker->regexify('OTM[0-9]{12}[A-Z]{4}'),
            'TOTAL_COST' => f_currency($quote?->repository->getTotalCost($sent->paying) ?? 1000.0),
            'EXPIRY' => f_date($quote?->expires ?? now()),
            'EVENT_NAME' => $quote?->event?->name ?? '',
            ...(new SettingsMail())->getShortcodes()
        ];
    }
}
