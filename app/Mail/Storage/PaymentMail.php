<?php

namespace App\Mail\Storage;

use App\Models\Order\Payment\Payment;

class PaymentMail extends TemplatedMail
{
    public function getShortcodes($model = null): array
    {
        $payment = $model instanceof Payment ? $model : null;
        return [
            'PAYMENT_AMOUNT' => f_currency($payment?->amount ?? $this->faker->numberBetween(100, 1000)),
            'PAYMENT_DATE' => f_date($payment?->paid_on ?? $this->faker->date),
            'PAYMENT_METHOD' => $payment?->paymentMethod->name ?? 'Demo Payment Method',
            'PAYMENT_TYPE' => $payment?->payment_type ?? 'Demo Payment Type',
            ...(new OrderMail())->getShortcodes($payment),
        ];
    }
}
