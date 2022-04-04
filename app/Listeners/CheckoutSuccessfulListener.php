<?php

namespace App\Listeners;

use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Order\Payment\PaymentMethod;
use App\Repository\OrderRepository;
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
            if (isset($intention) && !$intention->processed) {
                $order = OrderRepository::getOrderFromBookingReference($intention->reference);
                if (isset($order)) {
                    $payment = $intention->makePayment($data['amount'] / 100, PaymentMethod::firstOrCreate('Stripe'), $payload['created']);
                    $order->payments()->save($payment);
                    $intention->process();
                    $intention->processed = true;
                    $intention->save();
                    event(new PaymentCreatedEvent($payment));
                }
            }
        }
    }
}
