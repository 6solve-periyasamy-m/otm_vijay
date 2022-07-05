<?php

namespace App\Listeners;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Models\Booking\Booking;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Order\Payment\PaymentMethod;
use App\Repository\Model\Order\OrderRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class CheckoutSuccessfulListener implements ShouldQueue
{
    public function handle(WebhookCall $call)
    {
        $payload = $call->payload;
        $data = $payload['data']['object'];
        $metadata = $data['metadata'];
        if (key_exists('intention_id', $metadata)) {
            $intention = PaymentIntention::fetch($metadata['intention_id']);
            if (!isset($intention)) return;
            if (!$intention->processed) {
                $order = OrderRepository::getFromBookingReference($intention->reference);
                if (isset($order)) {
                    $payment = $intention->makePayment($data['amount'] / 100, PaymentMethod::findOrCreate('Stripe'), $payload['created']);
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
                    $payment = $intention->makePayment($data['amount'] / 100, PaymentMethod::findOrCreate('Stripe'), $payload['created']);
                    $order->payments()->save($payment);
                    $intention->processed = true;
                    $intention->save();
                    event(new OrderCreatedEvent($order));
                }
            }
        }
    }
}
