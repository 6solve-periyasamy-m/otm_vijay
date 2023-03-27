<?php

namespace App\Mail\Storage;

use App\Models\Order\OrderCustomer;

class OrderCustomerMail extends TemplatedMail
{
    public function getShortcodes($model = null): array
    {
        $orderCustomer = $model instanceof OrderCustomer ? $model : null;
        $customer = $orderCustomer?->customer;
        return [
            'CUSTOMER_TITLE' => $customer?->title ?? $this->faker->title,
            'CUSTOMER_FIRST_NAME' => $customer?->first_name ?? $this->faker->firstName,
            'CUSTOMER_MIDDLE_NAMES' => $customer?->middle_names ?? $this->faker->firstName,
            'CUSTOMER_LAST_NAME' => $customer?->last_name ?? $this->faker->lastName,
            'CUSTOMER_PASSPORT_EXPIRY_DATE' => f_currency($customer?->passport_expiry_date ?? $this->faker->date),
            ...(new OrderMail())->getShortcodes($orderCustomer),
        ];
    }
}
