<?php

namespace App\Http\Gateways;

use App\Models\Order;
use Stripe\Checkout\Session;

class StripeGateway extends Gateway
{
    public static function checkout(array $items, string $reference, string $paymentType, int $customerId)
    {
        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('app.currency'),
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['cost'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }
        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'metadata' => [
                'payment_type' => $paymentType,
                'booking_reference' => $reference,
                'customer_id' => $customerId,
            ],
            'success_url' => route('payment.gateway.stripe.success'),
            'cancel_url' => route('payment.gateway.stripe.cancelled'),
        ]);

        return response(null, 303, ['Location' => $session->url,]);
    }
}
