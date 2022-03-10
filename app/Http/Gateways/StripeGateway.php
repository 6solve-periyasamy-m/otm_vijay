<?php

namespace App\Http\Gateways;

use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentIntention;
use App\Repository\SettingsRepository;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class StripeGateway extends Gateway
{
    public static function checkout(array $items, Order $order, string $paymentType, int $customerId)
    {
        $lineItems = [];
        $amount = 0;
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
            $amount += ($item['cost'] * 100);
        }
        $intention = PaymentIntention::build(Customer::find($customerId), $order->booking_reference, $paymentType);
        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => [
                    'intention_id' => $intention->id,
                ],
            ],
            'metadata' => [
                'intention_id' => $intention->id,
            ],
            'success_url' => route('payment.gateway.stripe.success'),
            'cancel_url' => route('payment.gateway.stripe.cancelled'),
        ]);

        return response(null, 303, ['Location' => $session->url,]);
    }
}
