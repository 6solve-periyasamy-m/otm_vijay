<?php

namespace App\Http\Gateways;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Order\Payment\PaymentMethod;
use App\Repository\Model\Order\OrderRepository;
use Stripe\Checkout\Session;

class StripeGateway extends Gateway
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
    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        $lineItems = [];
        foreach ($items as $item) { $lineItems[] = $item->toStripe(); }

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
            'success_url' => $success ?? $this->success,
            'cancel_url' => $this->cancelled,
        ]);

        return $session->url;
    }

    public function process(string $reference, float $amount, mixed $created = null): void
    {
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $amount, 'Stripe', $created);
    }
}
