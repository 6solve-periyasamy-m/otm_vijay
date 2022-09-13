<?php

namespace App\Http\Gateways;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Models\Booking\Booking;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Order\Payment\PaymentMethod;
use App\Repository\Model\Order\OrderRepository;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

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
    public function checkout(array $items, PaymentIntention $intention, string $success = null): string
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
        if (!$intention->processed) {
            $order = OrderRepository::getFromBookingReference($intention->reference);
            if (isset($order)) {
                $payment = $intention->makePayment($amount / 100, PaymentMethod::findOrCreate('Stripe'), $created ?? now());
                $order->payments()->save($payment);
                $intention->process();
                $intention->processed = true;
                $intention->save();
                event(new PaymentCreatedEvent($payment));
                return;
            }
            $booking = Booking::where('token', $intention->reference)->first();
            if (isset($booking)) {
                $order = $booking->repository->convertToOrder(now());
                $intention->customer_id = $order->leadBooker->customer_id;
                $intention->save();
                $payment = $intention->makePayment($amount / 100, PaymentMethod::findOrCreate('Stripe'), $created ?? now());
                $order->payments()->save($payment);
                $intention->processed = true;
                $intention->save();
                event(new OrderCreatedEvent($order));
            }
        }
    }
}
