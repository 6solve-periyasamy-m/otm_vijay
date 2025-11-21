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
        $currency =
            (($intention->getRelatedModel() instanceof Order) ? $intention->getRelatedModel()?->currency?->code : null) ?? config('app.currency');
        $currencyKeys = config('app.gateways.stripe.currencies.' . strtoupper($currency), []);
        $secret = $currencyKeys['secret'] ?? config('app.gateways.stripe.secret');
        $currency = strtoupper($currency);
        $lineItems = [];
        foreach ($items as $item) { $lineItems[] = $item->toStripe($currency); }

        $stripe = new StripeClient($secret);

        $session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => [
                    'intention_id' => $intention->id,
                ],
            ],
            'currency' => $currency,
            'metadata' => [
                'intention_id' => $intention->id,
                'booking_reference' => $intention->reference,
            ],
            'success_url' => $success ?? $this->success,
            'cancel_url' => $this->cancelled,
        ]);

        return $session->url;
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
