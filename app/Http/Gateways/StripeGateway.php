<?php

namespace App\Http\Gateways;

use App\Http\Gateways\Interfaces\SupportsRedirect;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeGateway extends Gateway implements SupportsRedirect
{
    private string $success;
    private string $cancelled;

    public function __construct(?string $success = null, ?string $cancelled = null)
    {
        $this->success = $success ?? route('payment.gateway.stripe.success');
        $this->cancelled = $cancelled ?? route('payment.gateway.stripe.cancelled');
    }

    /**
     * @inheritDoc
     * @throws ApiErrorException
     * @noinspection PhpArrayKeyDoesNotMatchArrayShapeInspection
     */
    public function getRedirect(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getCheckout($items, $intention, $customer, $success)->url;
    }

    public function getCheckoutSecret(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null, string|null $currency = null): string
    {
        return $this->getCheckout($items, $intention, $customer, $success, 'custom', $currency)->client_secret;
    }

    /**
     * @throws ApiErrorException
     */
    private function getCheckout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null, string $ui = 'hosted', string|null $currency = null)
    {
        $currency = $currency ?? (($intention->getRelatedModel() instanceof Order) ? $intention->getRelatedModel()?->currency?->code : null);
        $currencyKeys = config('app.gateways.stripe.currencies.' . strtoupper($currency), []);
        $secret = $currencyKeys['secret'] ?? config('app.gateways.stripe.secret');
        $currency = strtolower(empty($currency) ? \Settings::currency()?->code : $currency);
        $lineItems = [];
        foreach ($items as $item) { $lineItems[] = $item->toStripe($currency); }

        $data = [
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => [
                    'intention_id' => $intention->id,
                ],
            ],
            'currency' => $currency,
            'customer_email' => $customer?->email_address,
            'metadata' => [
                'intention_id' => $intention->id,
                'booking_reference' => $intention->reference,
            ],
            'ui_mode' => $ui,
        ];

        if ($ui === 'custom') {
            $data = [
                ...$data,
                'return_url' => $success ?? $this->success,
            ];
        } else {
            $data = [
                ...$data,
                'success_url' => $success ?? $this->success,
                'cancel_url' => $this->cancelled,
            ];
        }
        $stripe = new StripeClient($secret);

        return $stripe->checkout->sessions->create($data);
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getRedirect($items, $intention, $customer, $success);
    }

    public function process(string $reference, float $amount, mixed $created = null, string|null $currency = null): void
    {
        $currency = $currency ?? config('app.currency');
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $amount, 'Stripe', $created, $currency);
    }
}
