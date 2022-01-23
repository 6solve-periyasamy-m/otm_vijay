<?php

namespace App\Listeners;

use App\Events\PaymentMadeEvent;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Repository\OrderRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class CheckoutSuccessfulListener implements ShouldQueue
{
    public function handle(WebhookCall $call)
    {
        $payload = $call->payload;
        $data = $payload['data']['object'];
        $metadata = $data['metadata'];
        if (key_exists('payment_type', $metadata) && key_exists('booking_reference', $metadata)) {
            $order = OrderRepository::getOrderFromBookingReference($metadata['booking_reference']);
            if (isset($order)) {
                $payment = Payment::make([
                    'payment_method_id' => PaymentMethod::firstOrCreate('Stripe')->id,
                    'paid_on' => Carbon::parse($payload['created']),
                    'amount' => $data['amount_total'] / 100,
                    'payment_type' => $metadata['payment_type'],
                ]);
                $order->payments()->save($payment);
                event(new PaymentMadeEvent($payment));
            }
        }
    }
}
