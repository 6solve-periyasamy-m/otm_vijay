<?php

namespace App\Http\Gateways;

use App\Http\Gateways\Interfaces\SupportsRedirect;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;
use Stripe\Checkout\Session;

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
     */
    public function getRedirect(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getCheckout($items, $intention, $customer, $success)->url;
    }

    public function getCheckoutSecret(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getCheckout($items, $intention, $customer, $success)->client_secret;
    }

    private function getCheckout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): Session
    {
        $lineItems = [];
        foreach ($items as $item) { $lineItems[] = $item->toStripe(); }

        return Session::create([
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
            'success_url' => $success ?? $this->success,
            'cancel_url' => $this->cancelled,
        ]);
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getRedirect($items, $intention, $customer, $success);
    }

    public function process(string $reference, float $amount, mixed $created = null): void
    {
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $amount, 'Stripe', $created);
    }
}
